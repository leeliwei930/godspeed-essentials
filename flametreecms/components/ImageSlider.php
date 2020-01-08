<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;

class ImageSlider extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'ImageSlider Component',
            'description' => 'No description provided yet...'
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

    public function loadSlideOptions()
    {
        return \GodSpeed\FlametreeCMS\Models\ImageSlider::all()->pluck('label', 'id')->toArray();
    }

    public function slide()
    {
        return \GodSpeed\FlametreeCMS\Models\ImageSlider::find($this->property('label'));
    }

}
