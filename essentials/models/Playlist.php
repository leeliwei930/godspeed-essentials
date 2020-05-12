<?php namespace GodSpeed\Essentials\Models;

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
    public $table = 'godspeed_essentials_playlists';

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
        'products' => [
            Product::class, "table" => "godspeed_essentials_products"
        ]
    ];
    public $belongsTo = [];

    public $belongsToMany = [
        'videos' => [
            "\GodSpeed\Essentials\Models\Video", "table" => "godspeed_essentials_video_playlists"
        ],
    ];

    public $rules = [
        'name' => "required|unique:godspeed_essentials_playlists,name|between:5,255"
    ];

    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
