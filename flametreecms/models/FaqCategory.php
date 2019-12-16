<?php namespace GodSpeed\FlametreeCMS\Models;

use Model;

/**
 * FaqCategory Model
 */
class FaqCategory extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_faq_categories';

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

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        'faqs' => [
            "GodSpeed\FlametreeCMS\Models\Faq",
            "table" => "godspeed_flametreecms_faq_categories_pivot"
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
