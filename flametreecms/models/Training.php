<?php namespace GodSpeed\FlametreeCMS\Models;

use Model;
use GodSpeed\FlametreeCMS\Models\Playlist;
/**
 * Training Model
 */
class Training extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_trainings';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['title', 'content_html', 'video_playlist_id'];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'title' => [
            'required',
            'between:5,255',
        ],
        'slug' => [
            'required',
            'between:5,255'
        ],
        'video_playlist_id' => [
            'nullable', 'exists:godspeed_flametreecms_playlists,id'
        ]
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [

    ];
    public $hasMany = [];
    public $belongsTo = [
        'video_playlist' => [
            \GodSpeed\FlametreeCMS\Models\Playlist::class
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [
        'documents' => [
            'System\Models\File'
        ]
    ];

    public function getVideoPlaylistOptions(){
        return Playlist::all()->pluck('name' , 'id');
    }
}
