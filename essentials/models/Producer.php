<?php namespace GodSpeed\Essentials\Models;

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
    public $table = 'godspeed_essentials_producers';


    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name' , "origin", "website", "featured_image", "slug"
    ];

    protected $rules = [
        "name" => [
            "required", "between:5,255"
        ],
        "origin" => 'max:255',
        "featured_image" => "max:255",
        "website" => 'max:255'
    ];
    /**
     * @var array Relations
     */
    public $hasOne = [];

    public $hasMany = [
        "products" => [
            Product::class,
            "table"  => "godspeed_essentials_products"
        ]
    ];
    public $belongsToMany = [
        "producer_categories" => [
            'GodSpeed\Essentials\Models\ProducerCategory' ,
            "table" => "godspeed_essentials_producer_categories_pivot"
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
