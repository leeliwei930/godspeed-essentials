<?php namespace GodSpeed\Essentials\Components;

use Cms\Classes\ComponentBase;

class ProducerCategoryList extends ComponentBase
{
    public $producerCategories;

    public function componentDetails()
    {
        return [
            'name'        => 'Product Category List',
            'description' => 'Render a list of products category'
        ];
    }

    public function defineProperties()
    {
        return [
            "show_producers" => [
                "title" => "Show Producers",
                "type" => "checkbox"
            ]
        ];
    }

    public function onRun()
    {
        $this->producerCategories = $this->all();
    }

    public function all()
    {

        if ($this->property('show_producers')) {
            $productCategories = \GodSpeed\FlametreeCMS\Models\ProducerCategory::with(['producers'])->get();
        } else {
            $productCategories = \GodSpeed\FlametreeCMS\Models\ProducerCategory::all();
        }
        return $productCategories;
    }
}
