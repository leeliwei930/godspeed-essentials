<?php namespace GodSpeed\Essentials\Models;

use Illuminate\Validation\Rule;
use Model;
use October\Rain\Database\Traits\Validation;

/**
 * FaqCategory Model
 */
class FaqCategory extends Model
{
    use Validation;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_essentials_faq_categories';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name' , 'slug'
    ];

    protected $rules = [
        'name'  => [
            "required",
            "between:3,255",
            "unique:godspeed_essentials_faq_categories,name"
        ],
        "slug" => [
            "required",
            "unique:godspeed_essentials_faq_categories,slug"
        ]
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        'faqs' => [
            "GodSpeed\Essentials\Models\Faq",
            "table" => "godspeed_essentials_faq_categories_pivot"
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
