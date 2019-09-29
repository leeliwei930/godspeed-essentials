<?php namespace GodSpeed\FlametreeCMS\Models;

use Illuminate\Validation\Rule;
use Model;

/**
 * ProductCategory Model
 */
class ProductCategory extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_product_categories';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @var array Relations
     */
    public $rules = [
        "name" => [
            "required" , "between:3,255" ,"unique:godspeed_flametreecms_product_categories,name"
        ]
    ];
    public $hasOne = [];
    public $hasMany = [
        "products" => [
            'GodSpeed\FlameTreeCms\Models\Product' , "name" => "products"
        ]
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
