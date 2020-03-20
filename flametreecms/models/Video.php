<?php namespace GodSpeed\FlametreeCMS\Models;

use GodSpeed\FlametreeCMS\Models\Video as VideoModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Model;
use October\Rain\Exception\ValidationException;

/**
 * Video Model
 */
class Video extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_videos';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        "embed_id" , "duration" , "featured_image" , "description" , "type" , "title"
    ];

    protected $appends = [
        'video_url', 'featured_image_url'
    ];


    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        'playlists' => [
            'GodSpeed\FlametreeCMS\Models\Playlist' , "table" => "godspeed_flametreecms_video_playlists"
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

     const VALIDATION_RULES  = [
        'title' => 'required_if:type,video',
        'type' => 'required|in:video,youtube,vimeo',
        'duration' => 'numeric',
        'embed_id' => 'required',
     ];

     const VALIDATION_MSG = [
        'embed_id' => "The video path is required when the type is a video",
     ];


     public function getTypeOptions()
     {
         return [
            "youtube" => "YouTube",
            "vimeo" => "Vimeo",
            "video" => "Media Gallery"
         ];
     }

     public function getVideoUrlAttribute()
     {
         if ($this->attributes['type'] === 'video') {
             return url("storage/app/media/" . $this->attributes['embed_id'] ?? 'default.png');
         } else {
             return $this->attributes['embed_id'] ?? null;
         }
     }


     public function getFeaturedImageUrlAttribute()
     {

         if ($this->attributes['type'] === 'video') {
             return url("storage/app/media/" . $this->attributes['featured_image'] ?? null);
         } else {
             return $this->attributes['featured_image'] ??  url("storage/app/media/default.png");
         }
     }

//
     public function filterFields($fields, $context = null)
     {
         switch ($context) {
             case "update":
             case "create":
                 $this->renderForm($fields);
                 break;
         }
     }

     private function hasYouTubeDataAPIKey()
     {
         $youtubeDataAPIKey = Settings::get('youtube_data_api_key', null);

         return !is_null($youtubeDataAPIKey) && strlen($youtubeDataAPIKey) > 0;
     }

     private function renderForm($fields)
     {
         switch ($this->type) {
             case "youtube":
                 if ($this->hasYouTubeDataAPIKey()) {
                     $fields->{'embed_id'}->type = "text";
                     $fields->{'title'}->readOnly = true;
                     $fields->{'description'}->readOnly = true;
                 } else {
                     $fields->{'embed_id'}->type = "text";
                     $fields->{'title'}->readonly = false;
                     $fields->{'description'}->readonly = false;
                 }
                 break;
             case "vimeo":
                 $fields->{'embed_id'}->type = "text";
                 $fields->{'title'}->readOnly = true;
                 $fields->{'description'}->readOnly = true;
                 break;
             default:
                 $fields->{'title'}->readonly = false;
                 $fields->{'description'}->readonly = false;
         }
     }

     public function beforeSave()
     {
         $validator = Validator::make(
             $this->toArray(),
             self::VALIDATION_RULES,
             self::VALIDATION_MSG
         );

         if ($validator->fails()) {
             throw new \ValidationException($validator);
         }
         if ($this->hasYouTubeDataAPIKey() || $this->type === "vimeo") {
             $api = \GodSpeed\FlametreeCMS\Utils\VideoMeta\Video::make($this->toArray());
             $res = $api->get();

             if ($res['status'] === \GodSpeed\FlametreeCMS\Utils\VideoMeta\Video::OK) {
                 $data = [
                     'type' => $this->type,
                     'embed_id' => $res['embed_id'],
                     'duration' => $res['duration'],
                     'featured_image' => $res['featured_image'],
                     'title' => $res['title'],
                     'description' => $res['description'],
                 ];

                 $this->embed_id = $data['embed_id'];
                 $this->duration = $data['duration'];
                 $this->featured_image = $data['featured_image'];
                 $this->title = $data['title'];
                 $this->description = $data['description'];
             } else {
                 throw new ValidationException([
                     'embed_id' => "Invalid video id or youtube data API Key"
                 ]);
             }
         }
     }
}
