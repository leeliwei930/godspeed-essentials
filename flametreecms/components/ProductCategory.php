<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;

class ProductCategory extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Product Category',
            'description' => 'Return a list of products category'
        ];
    }

    public function defineProperties()
    {
        return [
            "Show Producers" => [
                "type" => "checkbox"
            ]
        ];
    }

    public function all(){

        if($this->property('Show Producers')){
            $productCategories = \GodSpeed\FlametreeCMS\Models\ProducerCategory::with(['producers'])->get();

        } else {
            $productCategories = \GodSpeed\FlametreeCMS\Models\ProducerCategory::all();

        }
        return $productCategories;
    }
}
