<?php namespace GodSpeed\FlametreeCMS\Components;

use Carbon\CarbonInterval;
use Cms\Classes\ComponentBase;
use October\Rain\Database\Relations\BelongsToMany;

class UpcomingEvents extends ComponentBase
{
    public $events = [];

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

        ];
    }
    public function prepareVars()
    {
        $events = $this->makeEventsCollection(optional($this->getUpcomingEventsQuery())->get());
        $this->events = $this->page['events'] = $events;
    }

    public function onRun(){
        $this->prepareVars();
    }

    private function getUpcomingEventsQuery()
    {
        $member = $this->getCurrentMemberSession();
        if (!is_null($member)) {
            $upperBoundDate = $this->getDateScopeUpperBound();
            return $member->groups()->with([
                'events' => function (BelongsToMany $query) use ($upperBoundDate) {
                    $query->whereBetween('started_at', [
                        now()->toDateString(),
                        $upperBoundDate->toDateString()
                    ]);
                }
            ]);
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
