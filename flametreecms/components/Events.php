<?php namespace GodSpeed\FlametreeCMS\Components;

use Carbon\CarbonInterval;
use Cms\Classes\ComponentBase;
use GodSpeed\FlametreeCMS\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use October\Rain\Database\Relations\BelongsToMany;

class Events extends ComponentBase
{
    /**
     * @var
     */
    public $events;
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Member Events',
            'description' => 'List all the events that is relate to the current member login session'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        $scope =  $this->getDefaultScopeOptions();

        return [
            'limit' => [
                'title' => "Maximum number of records",
                "description" => "0 as default, which is unlimited",
                'validationPattern' => '^[0-9]+$',
                'default' => "0"
            ],
            'list_only' => [
                'title' => "List Only",
                'type' => "dropdown",
                "options" => [
                    "upcoming" => "Upcoming Event",
                    "past" => "Past Events",
                    "all" => "All Event"
                ],
                'default' => $scope
            ],
            'max_period' => [
                'title' => "Max Period",
                'type' => "string",
                'validationPattern' => '^[0-9]+$',
                "default" => "5",
                "group"=> "Upcoming Schedule"

            ],
            'period' => [
                "title" => "Periodic",
                "type" => "dropdown",
                "group"=> "Upcoming Schedule",
                "options" => [
                    "days" => "Days",
                    "weeks" => "Weeks",
                    "months" => "Months",
                    "years" => "Years"
                ],
                "default" => "days"
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
     * Prepare component variable in page
     */
    public function prepareVars()
    {
        $events = $this->fetchEvents()->get();
        $this->events = $this->page['events'] =   $this->makeEventsCollection($events);
    }

    public function makeEventsCollection($records)
    {
        $collection =  collect($records)->flatMap(function ($roles) {
            return collect($roles['events']);
        });

        if($this->hasLimit()){
            $collection = $this->limitResult($collection);
        }

        return $collection;
    }

    private function getDefaultScopeOptions()
    {
        if (!is_null(get('scope'))) {
            return get('scope');
        } else {
            return "all";
        }
    }
    /**
     * Fetch meeting records.
     *
     * @return mixed
     */
    protected function fetchEvents()
    {
        $listOnly = $this->property('list_only');


        // show upcoming meeting minutes
        $query = $this->getEventsQuery();

        switch ($listOnly) {
            case "upcoming":
                $query = $this->getUpcomingEventsQuery();
                break;
            case "past":
                $query = $this->getPastEventsQuery();
                break;
            default:
                break;
        }





        // return all meetings
        return $query;
    }

    /**
     * Query that fetch all meetings
     * @return Event[]|Collection
     */
    private function getEventsQuery()
    {
        $member = $this->getCurrentMemberSession();
        $period = $this->property('period');
        $scope = $this->property('max_period');
        $scopeStartDate = now()->sub(CarbonInterval::$period($scope));
        $scopeEndDate = now()->add(CarbonInterval::$period($scope));
        if (!is_null($member)) {
            return $member->groups()->with([
                'events' => function (BelongsToMany $query) use ($scopeStartDate, $scopeEndDate) {
                    return $query->whereDate('started_at', ">", $scopeStartDate->toDateTimeString())
                        ->whereDate('started_at', "<", $scopeEndDate->toDateTimeString());

                }
            ]);
        }
        return [];
    }
    public function getScope(){
        return $this->property('list_only');
    }
    /**
     * Query that grabs upcoming meetings
     * @return mixed
     */
    private function getUpcomingEventsQuery()
    {
        $member = $this->getCurrentMemberSession();
        if (!is_null($member)) {
            return $member->groups()->with(['events' => function (BelongsToMany $query) {
                return $query->whereDate('started_at', "<", $this->getDateScope()->toDateTimeString())
                            ->whereDate('started_at', ">", now()->toDateTimeString());
            }]);
        }
        return [];
    }

    private function getPastEventsQuery()
    {
        $member = $this->getCurrentMemberSession();
        if (!is_null($member)) {
            return $member->groups()->with(['events' => function (BelongsToMany $query) {

                return $query->whereDate('started_at', "<", now()->toDateTimeString())
                    ->whereDate('started_at', ">", $this->getDateScope()->toDateTimeString());
            }]);
        }
        return [];
    }

    /**
     * Limit the query result
     * @param \October\Rain\Support\Collection $collection
     * @return mixed
     */
    protected function limitResult($collection)
    {
        $limit = $this->property('limit');

        return $collection->take($limit);
    }

    protected function hasLimit()
    {
        return $this->property('limit') > 0;
    }
    /**
     * Get the max scope of the event started date
     * @return Carbon
     */
    protected function getDateScope()
    {
        $maxPeriod = $this->property('max_period');
        $period = $this->property('period');
        $list_only = $this->property('list_only');
        // prepare the interval, it will be called as CarbonInterval::months/days/weeks(value)
        $maxPeriodInterval = CarbonInterval::$period($maxPeriod);

        // grab the current time and add the interval to get the max scoped event start date.
        switch ($list_only) {
            case "all":
                return now();
            case "upcoming":
                return now()->add($maxPeriodInterval);
            case "past":
                return now()->sub($maxPeriodInterval);
        }
    }

    /**
     * Get the current user session
     * @return \App\User|null
     */
    protected function getCurrentMemberSession()
    {
        return \Auth::user();
    }
}
