<?php namespace GodSpeed\FlametreeCMS\Models;

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

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        "video_playlist" => [
            'GodSpeed\FlameTreeCms\Models\VideoPlaylist'
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getTypeOptions()
    {
        return [
            "youtube" => "YouTube",
            "vimeo" => "Vimeo",
            "video" => "Media Gallery"
        ];
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
