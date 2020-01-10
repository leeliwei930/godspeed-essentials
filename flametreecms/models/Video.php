<?php namespace GodSpeed\FlametreeCMS\Models;

use Illuminate\Validation\Rule;
use Model;

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
                 if ($this->type === "video") {
                        $fields->{'title'}->readonly = false;
                        $fields->{'description'}->readonly = false;
                 } else {
                     $fields->{'embed_id'}->type = "text";
                     $fields->{'title'}->readOnly = true;
                     $fields->{'description'}->readOnly = true;
                 }
                 if ($this->type === "video") {
                     $fields->{'title'}->readonly = false;
                     $fields->{'description'}->readonly = false;
                 } else {
                     $fields->{'embed_id'}->type = "text";
                     $fields->{'title'}->readOnly = true;
                     $fields->{'description'}->readreadOnlyonly = true;
                 }
                 break;
         }
     }
}
