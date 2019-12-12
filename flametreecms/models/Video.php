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
        'embed_id.for.video' => 'required_if:type,video',
        'embed_id.for.platform' => "required_if:type,youtube,vimeo"
     ];

     const VALIDATION_MSG = [
        'embed_id.for.video.required_if' => "The video path is required when the type is a video",
        'embed_id.for.platform.required_if' =>
            "The embed id is required when the type is a platform media"
     ];


     public function getTypeOptions()
     {
         return [
            "youtube" => "YouTube",
            "vimeo" => "Vimeo",
            "video" => "Media Gallery"
         ];
     }

     public function getVideoUrlAttribute($value)
     {
         if ($this->attributes['type'] === 'video') {
             return url("storage/app/media/" . $this->attributes['embed_id']);
         } else {
             return $this->attributes['embed_id'];
         }
     }


     public function getFeaturedImageUrlAttribute($value)
     {

         if ($this->attributes['type'] === 'video') {
             return url("storage/app/media/" . $this->attributes['featured_image']);
         } else {
             return $this->attributes['featured_image'];
         }
     }


     public function filterFields($fields, $context = null)
     {
         switch ($context) {
             case "create":
                 if ($this->type === "video") {
                     $fields->{'embed_id[for][video]'}->hidden = false;
                     $fields->{'featured_image[for][video]'}->hidden = false;
                     $fields->title->hidden = false;
                     $fields->description->hidden = false;
                     $fields->title->readOnly = false;
                     $fields->description->readOnly = false;
                 } else {
                     $fields->{'embed_id[for][platform]'}->hidden = false;
                 }
                 break;
             case "update":
                 if ($this->type === "video") {
                     $fields->{'embed_id[for][video]'}->hidden = false;
                     $fields->{'featured_image[for][video]'}->hidden = false;
                     $fields->duration->hidden = false;
                     $fields->duration->readOnly = true;
                     $fields->{'embed_id[for][video]'}->value = $this->embed_id;
                     $fields->{'featured_image[for][video]'}->value = $this->featured_image;

                     $fields->title->hidden = false;
                     $fields->description->hidden = false;
                     $fields->title->readOnly = false;
                     $fields->description->readOnly = false;
                     $fields->type->default = $this->type;
                 } else {
                     $fields->{'embed_id[for][platform]'}->hidden = false;
                     $fields->{'embed_id[for][platform]'}->value = $this->embed_id;
                     $fields->title->hidden = false;
                     $fields->description->hidden = false;
                     $fields->type->default = $this->type;
                 }
                 break;
         }
     }
}
