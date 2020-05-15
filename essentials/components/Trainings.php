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


    /**
     * Generate pagination wrapper response
     * @param $items
     * @param int $perPage
     * @param null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
