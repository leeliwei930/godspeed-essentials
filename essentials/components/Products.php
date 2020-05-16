<?php namespace GodSpeed\Essentials\Components;

use Cms\Classes\ComponentBase;


use Cms\Classes\Page;
use GodSpeed\Essentials\Models\Producer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use October\Rain\Support\Collection;


/**
 * Class Products
 * @package GodSpeed\Essentials\Components
 */
class Products extends ComponentBase
{
    public $producer;
    public $products;
    public $productPage;
    public $productPageSlug;


    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'Producer\'s products',
            'description' => 'Display a set of products produce by the specific producers'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        return [
            'producer' => [
                'type' => 'string',
                'default' => '{{:producer_slug}}'
            ],
            'producer_search_key' => [
                'type' => 'dropdown',
                'options' => [
                    'id' => "ID",
                    'slug' => "Slug"
                ],
                'default' => 'slug'
            ],
            'products_per_page' => [
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'perPage value must be a numeric',
                'default' => 10
            ],
            'product_page' => [
                'title' => "Product Page",
                'type' => 'dropdown'
            ],
            'product_page_slug' => [
                'title' => "Product Page Slug",
                'type' => 'string',
                'default' => 'slug'
            ]
        ];
    }

    /**
     *
     */
    public function onRun()
    {

        $this->prepareVars();

    }

    public function throw404()
    {

        $this->setStatusCode(404);
        return $this->controller->run('404');

    }

    /**
     *
     */
    public function prepareVars()
    {
        $this->producer = $this->page['producer'] = $this->fetchProducerProducts();
        $this->page['products'] = $this->products;
        $this->productPage = $this->page['productPage'] = $this->property('product_page');
        $this->productPageSlug = $this->page['productPageSlug'] = $this->property('product_page_slug');
    }

    /**
     * Fetch the producer with their products details and categories
     * @return |null
     */
    public function fetchProducerProducts()
    {
        $producerInfo = null;
        $pageUrl = $this->page->url;
        $options = ['path' => $pageUrl];
        $perPage = $this->property('products_per_page');
        $currentPage = $this->getCurrentPage();
        if ($this->getProducerSearchKey() === 'id') {
            $producerInfo = Producer::find($this->getProducerParameterValue());
            if (is_null($producerInfo)) {
                return $this->throw404();
            }
            $producer_products = $producerInfo->products()->with('categories')->isActive()->paginate($perPage, $currentPage);

            $this->products = $producer_products;
        }

        if ($this->getProducerSearchKey() === 'slug') {
            $producerInfo = Producer::where('slug', $this->getProducerParameterValue())
                ->first();
            if (is_null($producerInfo)) {
                return $this->throw404();
            }
            $producer_products = $producerInfo->products()->with('categories')->isActive()->paginate($perPage, $currentPage);

            $this->products = $producer_products;

        }
        return $producerInfo;
    }

    /**
     * @return string
     */
    private function getProducerSearchKey()
    {
        return $this->property('producer_search_key');
    }

    /**
     * @return string
     */
    private function getProducerParameterValue()
    {
        return $this->property('producer');

    }



    /**
     * @return int|mixed
     */
    public function getCurrentPage()
    {
        return (\Input::has('page')) ? \Input::get('page') : 1;
    }

    public function getProductPageUrl($slug){
        return Page::url($this->productPage, [$this->productPageSlug => $slug]);
    }
}
