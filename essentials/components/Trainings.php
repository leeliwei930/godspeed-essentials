<?php namespace GodSpeed\Essentials\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use GodSpeed\Essentials\Models\Training;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use October\Rain\Support\Collection;
use RainLab\User\Facades\Auth;

/**
 * Class Trainings
 * @package GodSpeed\Essentials\Components
 */
class Trainings extends ComponentBase
{
    /**
     * @var
     */
    public $trainings;
    public $trainingPage;
    public $trainingPageSlug;


    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'Trainings',
            'description' => 'Display all the trainings data that only visible by authenticated user groups.'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        return [
            'perPage' => [
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'perPage value must be a numeric',
                'default' => 10
            ],
            'training_page' => [
                'title' => "Training Page",
                'type' => 'dropdown',
                'options' => Page::getNameList()
            ],
            'training_page_slug' => [
                'title' => "Training Page Slug",
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

    /**
     *
     */
    public function prepareVars()
    {
        $this->page['trainings'] = $this->trainings = $this->fetchTrainings();
        $this->page['trainingPage'] = $this->trainingPage = $this->property('training_page');
        $this->page['trainingPageSlug'] = $this->trainingPageSlug = $this->property('training_page_slug');
    }

    /**
     * @return LengthAwarePaginator
     */
    public function fetchTrainings()
    {

        if (!is_null($this->getCurrentMemberLoginSession())) {
            return $this->getLoggedInUserTrainings();
        }
        return $this->paginateAllTrainings();

    }

    /**
     * Return all paginated trainings
     * @return mixed
     */
    public function paginateAllTrainings()
    {
        return Training::public()->paginate($this->property('perPage'), $this->getCurrentPage());
    }

    /**
     * Fetch all the trainings based on current logged in user's roles
     * @return LengthAwarePaginator
     */
    public function getLoggedInUserTrainings()
    {
        return Training::userGroup()->paginate($this->property('perPage'), $this->getCurrentPage());
    }

    private function getCurrentMemberLoginSession()
    {
        return Auth::user();
    }

    /**
     * @return int|mixed
     */
    public function getCurrentPage()
    {
        return (\Input::has('page')) ? \Input::get('page') : 1;
    }


    public function getTrainingPageUrl($slug){
        return Page::url($this->trainingPage, [$this->trainingPageSlug => $slug]);
    }
}
