<?php namespace GodSpeed\Essentials\Components;

use Cms\Classes\ComponentBase;

class ImageSlider extends ComponentBase
{
    public $slider;
    public function componentDetails()
    {
        return [
            'name'        => 'Carousel',
            'description' => 'Render a image slider or carousel based on the given label property'
        ];
    }

    public function defineProperties()
    {
        $slidesOptions = $this->loadSlideOptions();
        return [
            "label" => [
                "type" => "dropdown",
                "options" => $slidesOptions
            ]
        ];
    }

    public function onRun()
    {
        $this->slider = $this->loadSlider();
    }

    public function loadSlideOptions()
    {
        return \GodSpeed\Essentials\Models\ImageSlider::all()->pluck('label', 'id')->toArray();
    }

    protected function loadSlider()
    {
        return \GodSpeed\Essentials\Models\ImageSlider::find($this->property('label'));
    }



}
