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
            "Show Products" => [
                "type" => "checkbox"
            ]
        ];
    }

    public function all(){

        if($this->property('Show Products')){
            $productCategories = \GodSpeed\FlametreeCMS\Models\ProductCategory::with(['products'])->get();

        } else {
            $productCategories = \GodSpeed\FlametreeCMS\Models\ProductCategory::all();

        }
        return $productCategories;
    }
}
