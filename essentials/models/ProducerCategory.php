<?php namespace GodSpeed\Essentials\Models;

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
    public $table = 'godspeed_essentials_producer_categories';

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
            "required" , "between:3,255" ,"unique:godspeed_essentials_producer_categories,name"
        ]
    ];
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        "producers" => [
            'GodSpeed\Essentials\Models\Producer' ,
            "table" => "godspeed_essentials_producer_categories_pivot"
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'icon' => [
            'System\Models\File'
        ]
    ];
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

                        'url' => $producer->slug,
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
