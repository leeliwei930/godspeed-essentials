<?php namespace GodSpeed\Essentials\Components;

use Carbon\CarbonInterval;
use Carbon\Exceptions\InvalidDateException;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use GodSpeed\Essentials\Models\Event;
use GodSpeed\Essentials\Plugin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use October\Rain\Database\Relations\BelongsToMany;
use RainLab\User\Models\UserGroup;

class Events extends ComponentBase
{
    /**
     * @var
     */
    public $events;
    public $eventPage;
    public $timelines;
    public $selectedTimeLine;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'Member Events',
            'description' => 'List all the events that is relate to the current member login session'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        $defaultMonthNameField = $this->getDefaultMonthNameField();
        return [
            'monthname_field' => [
                "title" => "Scoped Field",
                "type" => "string",
                "default" => $defaultMonthNameField
            ],
            'event_page' => [
                'title' => "Event Detail Page",
                'type' => "dropdown",
                'options' => Page::getNameList()
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

        $this->timelines = $this->page['timelines'] = $this->getTimelineLabel();
        $this->page['monthname_key'] = $this->getMonthNameKey();

        $events = $this->getEventsQuery();
        $eventCollection = $this->makeEventsCollection($events);

        $this->events = $this->page['events'] = $eventCollection;
        $this->eventPage = $this->page['eventPage'] = $this->property('event_page');
    }

    public function makeEventsCollection($records)
    {

        return collect($records)->flatMap(function ($roles) {
            return collect($roles['events']);
        });
    }


    /**
     * Query that fetch all meetings
     * @return Event[]|Collection
     */
    private function getEventsQuery()
    {
        $member = $this->getCurrentMemberSession();

        $date = $this->getMonthlyScopedValue();
        $this->selectedTimeLine = $this->page['selectedTimeline'] = $date;
        try {
            $lowerBound = Carbon::parse($date)->firstOfMonth();
            $upperBound = Carbon::parse($date)->lastOfMonth();
            $this->selectedTimeLine = $lowerBound->format("d-m-Y");
        } catch (\Exception $exception) {
            // silent any invalid date format get passed in
            $date = now()->format("Y-m-d");
            $lowerBound = Carbon::parse($date)->firstOfMonth();
            $upperBound = Carbon::parse($date)->lastOfMonth();
            $this->selectedTimeLine = $lowerBound->format("d-m-Y");
        }
        if (!is_null($member)) {
            $userGroups = $member->groups()->get();

            collect($userGroups)->each(function ($group) use ($lowerBound, $upperBound) {
                $group['events'] = Event::whereHas('user_group', function ($query) use ($group) {
                    $query->whereIn('code', [$group->code, 'guest']);
                })->whereBetween('started_at', [
                    $lowerBound->toDateString(),
                    $upperBound->toDateString()
                ])->orWhereDoesntHave('user_group')->get();
            });

            return $userGroups;
        } else {
            $userGroups = UserGroup::where('code', 'guest')->get();
            collect($userGroups)->each(function ($group) use ($lowerBound, $upperBound) {
                $group['events'] = Event::whereHas('user_group', function ($query) use ($group) {
                    $query->where('code', $group->code);
                })->whereBetween('started_at', [
                    $lowerBound->toDateString(),
                    $upperBound->toDateString()
                ])->orWhereDoesntHave('user_group')->get();
            });

            return $userGroups;
        }
    }

    public function getMonthlyScopedValue()
    {
        // if there is no monthname presented, pick the latest one
        if (\Input::has($this->property('monthname_field'))) {
            return \Input::get($this->property('monthname_field'));
        } else {
            return now()->firstOfMonth()->format("d-m-Y");
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


    public function getTimelineLabel()
    {
        $member = $this->getCurrentMemberSession();
        $eventID = collect();

        if(!is_null($member)) {
            $userGroups =  $member->groups()->get();

            collect($userGroups)->each(function ($group) use ($eventID) {
                $events = Event::whereHas('user_group', function ($query) use ($group, $eventID) {
                    $query->whereIn('code', [$group->code, 'guest']);
                })->orWhereDoesntHave('user_group')->pluck('id');

                $events->each(function ($event) use ($eventID) {
                    $eventID->push($event);
                });
            });
        } else {
            $userGroups = UserGroup::where('code', 'guest')->get();
            collect($userGroups)->each(function ($group) use ($eventID) {
                $events = Event::whereHas('user_group', function ($query) use ($group, $eventID) {
                    $query->where('code', $group->code);
                })->orWhereDoesntHave('user_group')->pluck('id');

                $events->each(function ($event) use ($eventID) {
                    $eventID->push($event);
                });
            });
        }
        $eventID = $eventID->toArray();


        return Event::selectRaw('DATE_FORMAT(started_at, "%M, %Y") as timeline_label, DATE_FORMAT(started_at, "%m-%Y") as timeline_value, COUNT(*) as count')
            ->whereIn('id', $eventID)
            ->groupBy('timeline_label', 'timeline_value')
            ->orderByRaw("MIN(started_at) desc")
            ->get();
    }

    private function getDefaultMonthNameField()
    {
        return 'scope';
    }


    public function getMonthNameKey()
    {
        return $this->property('monthname_field');
    }
}
