<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;


use GodSpeed\FlametreeCMS\Models\Producer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use October\Rain\Support\Collection;


/**
 * Class Products
 * @package GodSpeed\FlametreeCMS\Components
 */
class Products extends ComponentBase
{
    public $producer;
    public $products;


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
            ]
        ];
    }

    /**
     *
     */
    public function onRun()
    {

        $this->prepareVars();
        $producerName = optional($this->producer)->name;

        if ($producerName) {
            $this->page->title = $producerName . "'s products";
        } else {
            $this->setStatusCode(404);
            return $this->controller->run('404');

        }
    }

    public function onRender()
    {


    }

    /**
     *
     */
    public function prepareVars()
    {
        $this->producer = $this->page['producer'] = $this->fetchProducerProducts();
        $this->page['products'] = $this->products;
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
        if ($this->getProducerSearchKey() === 'id') {
            $producerInfo = Producer::find($this->getProducerParameterValue());
            $products = $this->paginate($producerInfo->products()->isActive()->get(), $this->property('products_per_page'), $this->getCurrentPage(), $options);
            $this->products = $products;
        }

        if ($this->getProducerSearchKey() === 'slug') {
            $producerInfo = Producer::where('slug', $this->getProducerParameterValue())
                ->first();

            $products = $this->paginate($producerInfo->products()->isActive()->get(), $this->property('products_per_page'), $this->getCurrentPage(), $options);
            $this->products = $products;

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

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * @return int|mixed
     */
    public function getCurrentPage()
    {
        return (\Input::has('page')) ? \Input::get('page') : 1;
    }

}
