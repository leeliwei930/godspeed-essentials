<?php namespace GodSpeed\Essentials\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use GodSpeed\Essentials\Models\Producer;

class AllProducer extends ComponentBase
{
    public $producers;
    public $producerPage;
    public $producerPageSlug;

    public function componentDetails()
    {
        return [
            'name'        => 'All Producer Card',
            'description' => 'Render a collection of producer card'
        ];
    }

    public function defineProperties()
    {
        return [
            'include_categories' => [
                "title" => "Include Categories",
                "type" => "checkbox"
            ],
            'limit' => [
                'title' => "Limit",
                "type" => "string",
                "default" => "0",
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The limit property can contain only numeric symbols',
                'placeholder' => "If 0 all producers will be loaded."
            ],
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
        $this->producers = $this->page['producers'] =  $this->loadProducers();
        $this->producerPage = $this->page['producerPage'] = $this->property('producer_page');
        $this->producerPageSlug = $this->page['producerPageSlug'] = $this->property('producer_page_slug');
    }


    public function loadProducers()
    {
        $limit = (int) $this->property('limit') ?? 0;
        $include_categories = (int) $this->property('include_categories') ?? 0;
        if ($include_categories == 1) {
            $eloquentBuilder = Producer::with('producer_categories');
            if ($limit) {
                $eloquentBuilder = $eloquentBuilder->limit($limit)->get();
            } else {
                $eloquentBuilder = $eloquentBuilder->get();

            }
            return $eloquentBuilder;
        } else {
            if ($limit == 0) {
                $eloquentBuilder = Producer::all();
            } else {
                $eloquentBuilder = Producer::limit($limit)->get();
            }
            return $eloquentBuilder;

        }
    }
    public function getProducerPageUrl($slug){
        return Page::url($this->producerPage, [$this->producerPageSlug => $slug]);
    }
}
