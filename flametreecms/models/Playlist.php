<?php namespace GodSpeed\FlametreeCMS\Models;

use Model;
use October\Rain\Database\Traits\Validation;

/**
 * VideoPlaylist Model
 */
class Playlist extends Model
{
    use Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_playlists';

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

    ];
    public $belongsTo = [];

    public $belongsToMany = [
        'videos' => [
            "\GodSpeed\FlametreeCMS\Models\Video" , "table" => "godspeed_flametreecms_video_playlists"
        ]
    ];

    public $rules =  [
        'name' => "required|unique:godspeed_flametreecms_playlists,name"
    ];

    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
