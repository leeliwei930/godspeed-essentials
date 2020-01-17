<?php namespace GodSpeed\FlametreeCMS\Models;

use Cms\Classes\Page;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Model;

/**
 * ProductCategory Model
 */
class ProducerCategory extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_producer_categories';

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
            "required" , "between:3,255" ,"unique:godspeed_flametreecms_producer_categories,name"
        ]
    ];
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        "producers" => [
            'GodSpeed\FlametreeCMS\Models\Producer' ,
            "table" => "godspeed_flametreecms_producer_categories_pivot"
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    public static function resolveMenuItem($item, $url, $theme)
    {
        $items = ProducerCategory::with('producers')->get();

        $items = collect($items)->map(function ($_item) {
            return [
                'title' => $_item->name,
                'url' => Page::url('our-producers') . "#".Str::slug($_item->name),
                'isActive' => 0,
                'items' => collect($_item['producers'])->map(function ($producer) {
                    return [
                        'title' => $producer->name,

                        'url' => $producer->website,
                        'viewBag' => [

                            'origin' => $producer->origin
                        ]
                    ];
                })->toArray()
            ];
        })->toArray();

        $pageURL = Page::url('our-producers');
        $isActive = (Page::url('our-producers') === $url) ? 1 : 0;
        return [
            'title' => "Producers",
            'url' => $pageURL,
            'isActive' => $isActive,
            'items' => $items
        ];
    }
}
