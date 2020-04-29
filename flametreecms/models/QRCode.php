<?php namespace GodSpeed\FlametreeCMS\Models;

use Cms\Classes\Page;
use Illuminate\Validation\Rule;
use Model;

use SimpleSoftwareIO\QrCode\Facades\QrCode as QR;

/**
 * QRCode Model
 */
class QRCode extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_q_r_codes';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'resource_name', 'page', 'slugs', 'fields'
    ];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];
    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [
        'slugs', 'fields'
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
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [

    ];
    public $attachMany = [];



    public function beforeValidate(){
        $this->rules = [
            'resource_name' => [
                'required'
            ],
            'page' => [
                'required', Rule::in(array_keys(Page::getNameList()))
            ]
        ];
    }
    public function getPageOptions()
    {

        return Page::getNameList();
    }


    public function createQRUrl()
    {
        $page = $this->page;
        $slugs = collect($this->slugs)->pluck('value', 'key')->all();

        $params = collect($this->fields)->pluck('value', 'key')->all();

        if (!is_null($page)) {
            $parameters = http_build_query($params);

            if (count($params)) {
                return Page::url($page, $slugs) . '?' . $parameters;
            }
            return Page::url($page, $slugs);
        }
    }

    public function getPrototypeQRUrl()
    {
        $page = $this->page;
        if (!is_null($page)) {
            return url(Page::query()->whereFileName($page)->first()->url);
        }
    }

    public function getLinkAttribute()
    {
        return $this->createQRUrl();
    }

    public function makeQRCode($size = 250, $format = 'svg')
    {
        if ($this->hasQRCode()) {
            return QR::format($format)->size($size)->generate($this->createQRUrl());
        }
        return null;
    }


    public function hasQRCode()
    {
        return !empty($this->createQRUrl());
    }

}
