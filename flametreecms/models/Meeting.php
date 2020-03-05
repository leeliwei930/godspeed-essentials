<?php namespace GodSpeed\FlametreeCMS\Models;

use Model;

/**
 * Meeting Model
 */
class Meeting extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_meetings';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'title', 'description', 'started_at', 'ended_at', 'content_html'
    ];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'title' => [
            'required',
            'between:5,255',
            'unique:godspeed_flametreecms_meetings,title'
        ],
        'slug' => [
            'required',
            'between:5,255'
        ],
        'description' => [
            'max:65535'
        ],
        'started_at' => [
            'required',
            'date',
            'before:ended_at'
        ],
        'ended_at' => [
            'required',
            'date',
            'after:started_at'
        ]
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [
        'started_at' => "datetime:Y-m-d h:i a",
        'ended_at' => "datetime:Y-m-d h:i a"
    ];

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
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
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
}
