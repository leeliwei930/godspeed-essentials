<?php namespace GodSpeed\FlametreeCMS\Models;

use Model;

/**
 * ImageSlider Model
 */
class ImageSlider extends Model
{
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
        'label', 'autoplay', 'interval', 'show_navigation', 'responsive_size', 'size_w', 'size_h'
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

    public function getTitleAnimationOptions()
    {
        return \Config::get('godspeed.flametreecms::animate');
    }

    public function getCaptionAnimationOptions()
    {
        return \Config::get('godspeed.flametreecms::animate');
    }


}
