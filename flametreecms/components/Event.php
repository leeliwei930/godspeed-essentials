<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use October\Rain\Database\Relations\BelongsToMany;
use RainLab\User\Models\User;

class Event extends ComponentBase
{
    public $event;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Event',
            'description' => 'Return a specific event based on page url'
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
    public function fetchEvent()
    {
        switch ($this->property('searchBy')) {
            case 'slug':
                return $this->findEventBySlug($this->property('searchKey'));
            case 'id':
                return $this->findEventById($this->property('searchKey'));
        }
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    /**
     *
     */
    public function prepareVars()
    {
        $this->event = $this->page['event'] = $this->prepareEventsData($this->fetchEvent());
    }

    /**
     * @return User |null
     */
    protected function getCurrentMemberSession()
    {
        return \Auth::user();
    }

    /**
     * @param $slug
     * @return array
     */
    public function findEventBySlug($slug)
    {
        $user = $this->getCurrentMemberSession();
        if (!is_null($user) && $user instanceof  User) {
            return $user->groups()->with(['events' => function (BelongsToMany $query) use ($slug) {
                return $query->where('slug', $slug)->first();
            },'events.documents'])->get();
        }

        return null;
    }

    /**
     * @param $id
     * @return |null
     */
    public function findEventById($id)
    {
        $user = $this->getCurrentMemberSession();
        if (!is_null($user) && $user instanceof  User) {
            return $user->groups()->with(['events' => function (BelongsToMany $query) use ($id) {
                return $query->find($id);
            }, 'events.documents'])->get();
        }

        return null;
    }

    public function prepareEventsData($records)
    {
        // return unique result of event avoid duplication due to sometimes a user might have two roles
        return collect($records)->flatMap(function ($group) {
            // pluck out only events arrays
            return collect($group['events']);
        })->unique('id')->first();
    }
}
