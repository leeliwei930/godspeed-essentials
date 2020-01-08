<?php namespace GodSpeed\FlametreeCMS\Models;

use Illuminate\Validation\Rule;
use Model;
use October\Rain\Database\Traits\Validation;

/**
 * ImageSlider Model
 */
class ImageSlider extends Model
{
    use Validation;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_image_sliders';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    public $jsonable = ['slides'];
    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'label', 'autoplay', 'interval', 'show_navigation', 'responsive_size', 'size_w', 'size_h',
        'navigation_control_shape', 'show_navigation', 'autohide_navigation_control', 'navigation_control_bg_color',
        'navigation_color', 'show_indicator', "slides"
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
    public $attachOne = [];
    public $attachMany = [];

    public $rules = [
        "label" => [
            "required", "between:3,255", "unique:godspeed_flametreecms_image_sliders,label"
        ],
        "navigation_control_shape" => [
            "required", "between:3,255"
        ],
        "autoplay" => [
            "required", "boolean"
        ],
        "show_navigation" => [
            "required", "boolean"
        ],
        "autohide_navigation_control" =>[
            "required", "boolean"
        ],
        "interval" => [
            "required_if:autoplay,true", "numeric", "between:850,10000"
        ],
        "navigation_control_bg_color" => [
            'required',
        ],
        "responsive_size" => [
            "required" , "boolean"
        ],

        "size_w" => [
            "required_if:responsive_size,false",
            "numeric",
            "min:640",
            "max:65534"
        ],
        "size_h" => [
            "required_if:responsive_size,false",
            "numeric",
            "min:320",
            "max:65535"
        ],
        "navigation_color" => [
            "required"
        ],
        "show_indicator" => [
            "required",
            "boolean"
        ],
        "slides.*.image" => [
            "required"
        ],
        "slides.*.title" => [
            "between:0,65534"
        ],
        "slides.*.caption" => [
            "max:65534"
        ],
        "slides.*.titleAnimation" => [
            "required", "max:65534"
        ],
        "slides.*.captionAnimation" => [
            "required", "max:65534"
        ],
        "slides.*.showPrimaryActionButton" => [
            "required", "boolean"
        ],
        "slides.*.showSecondaryActionButton" => [
            "required", "boolean"
        ],
        "slides.*.primaryActionButtonText" => [
            "required_if:slides.*.showPrimaryActionButton,1"
        ],
        "slides.*.primaryActionButtonLink" => [
            "required_if:slides.*.showPrimaryActionButton,1"
        ],
        "slides.*.secondaryActionButtonLink" => [
            "required_if:slides.*.showSecondaryActionButton,1"
        ],
        "slides.*.primaryActionButtonType" => [
            "required_if:slides.*.showPrimaryActionButton,1"
        ],
        "slides.*.secondaryActionButtonType" => [
            "required_if:slides.*.showSecondaryActionButton,1"
        ],
        "slides.*.secondaryActionButtonText" => [
            "required_if:slides.*.showSecondaryActionButton,1"
        ]

    ];

    public function beforeValidate()
    {

        array_push(
            $this->rules['navigation_control_shape'],
            Rule::in(array_keys(
                $this->getNavigationControlShapeOptions()
            ))
        );

        array_push(
            $this->rules['slides.*.titleAnimation'],
            Rule::in(
                array_keys(
                    $this->getTitleAnimationOptions()
                )
            )
        );

        array_push(
            $this->rules['slides.*.captionAnimation'],
            Rule::in(
                array_keys(
                    $this->getTitleAnimationOptions()
                )
            )
        );


    }
    public function getTitleAnimationOptions()
    {
        return \Config::get('godspeed.flametreecms::animate');
    }

    public function getCaptionAnimationOptions()
    {
        return \Config::get('godspeed.flametreecms::animate');
    }

    public function getNavigationControlShapeOptions()
    {
        return [
            "circle" => "Circle",
            "square" => "Square"
        ];
    }
}
