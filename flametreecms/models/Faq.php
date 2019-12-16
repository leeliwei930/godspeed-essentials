<?php namespace GodSpeed\FlametreeCMS\Models;

use Model;

/**
 * faq Model
 */
class Faq extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_faqs';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'question' , 'answer'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        "faq_categories" => [
            "GodSpeed\FlametreeCMS\Models\FaqCategory",
            "table"=> "godspeed_flametreecms_faq_categories_pivot"
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}