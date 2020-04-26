<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;

use GodSpeed\FlametreeCMS\Models\Product as ProductModel;

class Product extends ComponentBase
{

    public $product;
    public $producer;

    public function componentDetails()
    {
        return [
            'name' => 'Product',
            'description' => 'Retrieve a product based on a given key or slug'
        ];
    }

    public function defineProperties()
    {
        return [
            'search_key' => [
                'type' => 'dropdown',
                "title" => "Search Key",
                'options' => [
                    'id' => "ID",
                    'slug' => "Slug"
                ],
                'default' => 'slug'
            ],
            'search_key_param' => [
                "title" => "Search Value",
                'type' => 'string',
                'default' => "{{:slug}}"
            ]
        ];
    }

    public function onRun()
    {
        $this->product = $this->page['product'] = $this->fetchProduct();
        $this->producer = $this->page['producer'] = optional($this->product)->producer;

        if (is_null($this->product)) {
            $this->setStatusCode(404);
            $this->controller->run("404");
        }

    }

    public function onRender()
    {
        $this->generatePageTitle();

    }

    private function fetchProduct()
    {
        $value = $this->property('search_key_param');
        switch ($this->property('search_key')) {
            case "slug":
                return $this->fetchProductBySlug($value);
            case "id":
                return $this->fetchProductById($value);
        }
        return null;
    }

    private function fetchProductById($id)
    {
        return ProductModel::with(['producer', 'categories', 'video_playlist.videos'])->find($id);
    }

    private function fetchProductBySlug($slug)
    {
        return ProductModel::with(['producer', 'categories', 'video_playlist.videos'])->where('slug', $slug)->first();
    }

    private function generatePageTitle()
    {
        $productName = $this->product->name;
        $producerName = $this->producer->name;
        $title = " $producerName  -  $productName ";
        $this->page->title =   $title. $this->page->title;

    }


}
