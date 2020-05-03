<?php namespace GodSpeed\FlametreeCMS\Models;

use Model;
use October\Rain\Database\Traits\Validation;
/**
 * faq Model
 */
class Faq extends Model
{
    use Validation;
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
    protected $rules = [

        "question" =>[
            "required", "between:5,255"
        ],
        "answer" => [
            "required", "min:5"
        ]

    ];
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
