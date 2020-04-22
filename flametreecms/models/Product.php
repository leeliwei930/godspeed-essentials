<?php namespace GodSpeed\FlametreeCMS\Models;

use Illuminate\Contracts\Validation\Rule;
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
        'name',
        'description',
        'type', // services or product
        'price',
        'has_stock_limit',
        'stock_left',
        'billing_cycle', // can be monthly, anually, forever, daily or weekly or installment
        'description',
        'features', //only available when the type is services
        'is_active',
        'currency'
    ];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'name' => 'required',
        'type' => 'required|in:service,product',
        'price' => 'required|min:0|numeric',
        'has_stock_limit' => 'required|boolean',
        'stock_left' => 'required_if:has_stock_limit,1|numeric|min:0',
        'is_active' => 'required|boolean',
        'billing_cycle' => [
            "required_if:type,services", "in:daily,weekly,monthly,annually"
        ],
        "features.*.name" => [
            'required'
        ],
        "features.*.description" => [
            'required'
        ]
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [
        'features', "images"
    ];

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
            Playlist::class,
            "key" => 'video_playlist_id'
        ]
    ];
    public $belongsToMany = [
        'categories' => [
            ProductCategory::class,
            "table" => "godspeed_flametreecms_products_categories",
            'key' => 'product_id',
            'otherKey' => 'product_category_id'
        ]
    ];
    public $morphTo = [
        'playlist videos'
    ];
    public $morphOne = [];
    public $morphMany = [
        'videos' => [
            Video::class ,
                'name' => "playlist videos"

        ]
    ];
    public $attachOne = [];
    public $attachMany = [];

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true)->orderBy('id', 'asc');
    }

    public function getTypeOptions()
    {
        return [
            'service' => "Service",
            'product' => "Product"
        ];
    }

    public function getBillingCycleOptions(){
        return [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'yearly' => "Yearly"
        ];
    }

    public function getCurrencyOptions(){
        return \Config::get('godspeed.flametreecms::currencies');
    }
}
