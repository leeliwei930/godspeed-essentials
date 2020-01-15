<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;
use GodSpeed\FlametreeCMS\Models\Producer;
use GodSpeed\FlametreeCMS\Models\ProducerCategory as ProducerCategoryModel;
class ProducerCategory extends ComponentBase
{

    public $producers;

    public function onRun()
    {
        $this->loadProducers();
    }

    public function componentDetails()
    {
        return [
            'name'        => 'Producer Category',
            'description' => 'Render a series of producer card component base on the selected category'
        ];
    }

    public function defineProperties()
    {
        $options = $this->getProducerCategoryOptions();

        return [

            'Producer Category' => [
                'type' => 'dropdown',
                'options' => $options
            ]

        ];
    }

    private function getProducerCategoryOptions()
    {
        return ProducerCategoryModel::all()->pluck('name', 'id')->toArray();
    }


    public function loadProducers()
    {
        $producer_category = $this->property('Producer Category');
        if ( !is_null($producer_category)) {
            $this->producers =  ProducerCategoryModel::with('producers')
                                ->find($producer_category);
        } else {
            $this->producers = Producer::all();
        }
    }
}
