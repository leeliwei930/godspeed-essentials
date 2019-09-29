<?php namespace GodSpeed\FlametreeCMS\Models;

use Model;
/**
 * Product Model
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Validation;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_products';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'producer_name' , "producer_origin", "producer_websites", "producer_logo", "product_category_id"
    ];

    protected $rules = [
        "producer_name" => 'required|between:5,255',
        "producer_origin" => 'max:255',
        "producer_logo" => "max:255",
        "producer_websites" => 'max:255'
    ];
    /**
     * @var array Relations
     */
    public $hasOne = [];

    public $hasMany = [];
    public $belongsTo = [
        "product_category" => [
            'GodSpeed\FlameTreeCms\Models\ProductCategory' ,
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
