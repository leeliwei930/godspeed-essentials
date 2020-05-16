<?php namespace GodSpeed\Essentials\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;

class ProducerCategoryList extends ComponentBase
{
    public $producerCategories;
    public $producerPage;
    public $producerPageSlug;

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

            'producer_page' => [
                'title' => "Producer Page",
                'type' => 'dropdown'
            ],
            'producer_page_slug' => [
                'title' => "Producer Page Slug",
                'type' => 'string',
                'default' => 'slug'
            ]
        ];
    }

    public function onRun()
    {
        $this->producerCategories = $this->page['producerCategories'] = $this->all();
        $this->producerPage = $this->page['producerPage'] = $this->property('producer_page');
        $this->producerPageSlug = $this->page['producerPageSlug'] = $this->property('producer_page_slug');
    }

    public function all()
    {


        $productCategories = \GodSpeed\Essentials\Models\ProducerCategory::with(['producers'])->get();

        return $productCategories;
    }

    public function getProducerPageUrl($slug){
        return Page::url($this->producerPage, [$this->producerPageSlug => $slug]);
    }
}
