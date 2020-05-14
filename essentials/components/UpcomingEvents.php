<?php namespace GodSpeed\Essentials\Components;

use Carbon\CarbonInterval;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use GodSpeed\Essentials\Models\Event;
use October\Rain\Database\Relations\BelongsToMany;
use RainLab\User\Models\UserGroup;

class UpcomingEvents extends ComponentBase
{
    public $events = [];
    public $eventPage;

    public function componentDetails()
    {
        return [
            'name' => 'Upcoming Events',
            'description' => 'List all the upcoming events '
        ];
    }

    public function defineProperties()
    {
        return [
            'scope' => [
                'group' => "Event scope",
                'title' => "Scope",
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'default' => 1

            ],
            'period' => [
                'group' => "Event scope",

                'title' => "Period",
                'type' => 'dropdown',
                'options' => [
                    'hours' => 'Hours',
                    'days' => 'Days',
                    'weeks' => 'Weeks',
                    'months' => 'Months',
                    'year' => 'Years'
                ],
                'default' => 'months'
            ],
            'event_page' => [
                'title' => "Event Detail Page",
                'type' => "dropdown",
                'options' => Page::getNameList()
            ]

        ];
    }
    public function prepareVars()
    {
        $events = $this->makeEventsCollection($this->getUpcomingEventsQuery());
        $this->events = $this->page['upcomingEvents'] = $events;
        $this->eventPage = $this->page['eventPage'] = $this->property('event_page');

    }

    public function onRun()
    {
        $this->prepareVars();
    }

    private function getUpcomingEventsQuery()
    {
        $member = $this->getCurrentMemberSession();
        $upperBoundDate = $this->getDateScopeUpperBound();

        if (!is_null($member)) {
            $userGroups = $member->groups()->get();

            collect($userGroups)->each(function ($group) use ($upperBoundDate) {
                $group['events'] = Event::whereHas('user_group', function ($query) use ($group) {
                    $query->whereIn('code', [$group->code, 'guest']);
                })->whereBetween('started_at', [
                    now()->toDateString(),
                    $upperBoundDate->toDateString()
                ])->orWhereDoesntHave('user_group')->get();
            });
            return $userGroups;
        } else {
            $userGroups = UserGroup::where('code', 'guest')->get();
            collect($userGroups)->each(function ($group) use ($upperBoundDate) {
                $group['events'] = Event::whereHas('user_group', function ($query) use ($group) {
                    $query->where('code', $group->code);
                })->whereBetween('started_at', [
                    now()->toDateString(),
                    $upperBoundDate->toDateString()
                ])->orWhereDoesntHave('user_group')->get();
            });

            return $userGroups;
        }
    }

    private function getDateScopeUpperBound()
    {
        $period = $this->property('period');
        $scopeVal = $this->property('scope');

        return now()->add(CarbonInterval::$period($scopeVal));
    }

    private function makeEventsCollection($records)
    {
        return collect($records)->flatMap(function ($roles) {
            return collect($roles['events']);
        });
    }

    protected function getCurrentMemberSession()
    {
        return \Auth::user();
    }
}
