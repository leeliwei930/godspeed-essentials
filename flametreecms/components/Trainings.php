<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use GodSpeed\FlametreeCMS\Models\Training;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use October\Rain\Support\Collection;

class Trainings extends ComponentBase
{
    public $trainings;
    public $paginationInfo;

    public function componentDetails()
    {
        return [
            'name' => 'Trainings',
            'description' => 'Display all the trainings data that only visible by authenticated user groups.'
        ];
    }

    public function defineProperties()
    {
        return [
            'perPage' => [
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'perPage value must be a numeric',
                'default' => 10
            ],
            'requireAuth' => [
                'title' => 'Require user authentication',
                'type' => 'checkbox',
                'default' => 1
            ]
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    public function prepareVars()
    {
        $this->page['trainings'] =  $this->trainings = $this->fetchTrainings();
    }

    public function fetchTrainings()
    {
        if ($this->requireAuth()) {
            return $this->getLoggedInUserTrainings();
        } else {
            return $this->paginateAllTrainings();
        }
    }

    public function paginateAllTrainings()
    {
        return Training::paginate($this->property('perPage'), $this->getCurrentPage())->toArray();
    }

    public function getLoggedInUserTrainings()
    {
        $paginationInfo = [];
        $userRoles = optional(\Auth::user())->groups()->with('trainings')->get()->toArray() ?? collect();



        $trainings = collect();

        collect($userRoles)->each(function ($role) use ($trainings) {
            collect($role['trainings'])->each(function ($training) use ($trainings) {
                $trainings->push($training);
            });
        });

        $records = $trainings->unique('id');

        $response = $this->paginate($records, $this->property('perPage'), $this->getCurrentPage(), [
            'path' => url($this->page->url)
        ])->toArray();
        return $response;
    }

    public function requireAuth()
    {
        return $this->property('requireAuth') == 1;
    }

    public function getCurrentPage()
    {
        return (\Input::has('page')) ? \Input::get('page') : 1;
    }



    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
