<?php namespace GodSpeed\FlametreeCMS\Models;

use Model;

/**
 * VideoPlaylist Model
 */
class VideoPlaylist extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_video_playlists';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['name'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        "videos" => [
            "GodSpeed\FlametreeCMS\Models\Video" , "name" => "videos"
        ]
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function addVideo($data)
    {

        return $this->videos()->create($data);
    }
}
