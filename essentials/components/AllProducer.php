<?php namespace GodSpeed\Essentials\Components;

use Cms\Classes\ComponentBase;
use GodSpeed\Essentials\Models\Producer;

class AllProducer extends ComponentBase
{
    public $producers;
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
            'Include Categories' => [
                "type" => "checkbox"
            ],
            'Limit' => [
                "type" => "string",
                "default" => "0",
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The Limit property can contain only numeric symbols',
                'placeholder' => "If 0 all producers will be loaded."

            ]
        ];
    }

    public function onRun()
    {
        $this->producers =  $this->loadProducers();
    }


    public function loadProducers()
    {
        $limit = (int) $this->property('Limit') ?? 0;
        $include_categories = (int) $this->property('Include Categories') ?? 0;
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
}
