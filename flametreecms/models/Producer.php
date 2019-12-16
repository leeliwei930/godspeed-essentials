<?php namespace GodSpeed\FlametreeCMS\Models;

use Model;
/**
 * Product Model
 */
class Producer extends Model
{
    use \October\Rain\Database\Traits\Validation;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_producers';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name' , "origin", "website", "logo"
    ];

    protected $rules = [
        "name" => 'required|between:5,255',
        "origin" => 'max:255',
        "logo" => "max:255",
        "website" => 'max:255'
    ];
    /**
     * @var array Relations
     */
    public $hasOne = [];

    public $hasMany = [];
    public $belongsToMany = [
        "producer_categories" => [
            'GodSpeed\FlametreeCMS\Models\ProducerCategory' ,
            "table" => "godspeed_flametreecms_producer_categories_pivot"
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}