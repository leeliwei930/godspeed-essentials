<?php namespace GodSpeed\Essentials\Components;

use Cms\Classes\ComponentBase;
use GodSpeed\Essentials\Models\Training as TrainingModel;

/**
 * Class Training
 * @package GodSpeed\Essentials\Components
 */
class Training extends ComponentBase
{
    /**
     * @var
     */
    public $training;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'Training',
            'description' => 'Render a single training record based onhe URL parameter'
        ];
    }

    /**
     * @return array
     */
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

    /**
     *
     */
    public function prepareVars()
    {
        $this->page['training'] = $this->training = $this->fetchTraining();
    }

    /**
     *
     */
    public function onRun()
    {
        $this->prepareVars();
    }

    /**
     * @return TrainingModel|TrainingModel[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function fetchTraining()
    {
        // checking type of key that used to search the record
        switch ($this->property('searchBy')) {
            case 'id':
                // fetch all relations
                return TrainingModel::userGroupTraining()
                    ->find($this->getSearchKey());
                break;
            case 'slug':
                return TrainingModel::userGroupTraining()
                    ->where('slug', $this->getSearchKey())
                    ->first();
                break;
        }
    }

    /**
     * Return the search key value could be slug or id
     * @return string
     */
    public function getSearchKey()
    {
        return $this->property('searchKey');
    }


    private function getCurrentLoginMemberSession(){
        return \Auth::user();
    }


}
