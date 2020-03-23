<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;
use GodSpeed\FlametreeCMS\Models\Training as TrainingModel;

class Training extends ComponentBase
{
    public $training;

    public function componentDetails()
    {
        return [
            'name' => 'Training Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'searchBy' => [
                'title' => "Search By",
                'type' => "dropdown",
                'options' => [
                    'id' => "ID",
                    'slug' => "slug"
                ],
                'default' => "slug"
            ],
            'searchKey' => [
                'title' => "Search Key",
                'type' => "string",
                'default' => "{{ :slug }}"
            ]
        ];
    }

    public function prepareVars()
    {
        $this->page['training'] = $this->training = $this->fetchTraining();
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    public function fetchTraining()
    {
        switch ($this->property('searchBy')) {
            case 'id':
                return TrainingModel::with(['documents', 'user_group', 'video_playlist.videos'])
                    ->find($this->getSearchKey());
                break;
            case 'slug':
                return TrainingModel::with(['documents', 'user_group',  'video_playlist.videos'])
                    ->where('slug', $this->getSearchKey())
                    ->first();
                break;
        }
    }

    public function getSearchKey()
    {
        return $this->property('searchKey');
    }

}
