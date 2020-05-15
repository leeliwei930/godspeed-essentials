<?php namespace GodSpeed\Essentials\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;

use October\Rain\Database\Relations\BelongsToMany;
use RainLab\User\Models\User;
use RainLab\User\Models\UserGroup;
use GodSpeed\Essentials\Models\Event as EventModel;

class Event extends ComponentBase
{
    public $event;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'Event',
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
        if (is_null($this->event)) {
            $this->setStatusCode(404);
            $this->controller->run("404");
        }
    }

    public function onRender()
    {
        $this->generatePageTitle();
    }

    /**
     *
     */
    public function prepareVars()
    {
        $this->event = $this->page['event'] = $this->fetchEvent();
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
        if (!is_null($user) && $user instanceof User) {
            return EventModel::with('documents')->userGroup()->where('slug', $slug)->first();
        } else {
            return EventModel::with('documents')->public()->where('slug', $slug)->first();
        }
    }

    /**
     * @param $id
     * @return |null
     */
    public function findEventById($id)
    {
        $user = $this->getCurrentMemberSession();
        if (!is_null($user) && $user instanceof User) {
            return EventModel::with('documents')->userGroup()->find($id);
        } else {
            return EventModel::with('documents')->public()->find($id);
        }
    }



    private function generatePageTitle()
    {
        $eventName = $this->event->title;


        $this->page->title = $eventName . $this->page->title;
    }
}
