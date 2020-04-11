<?php namespace GodSpeed\FlametreeCMS\Components;

use Carbon\CarbonInterval;
use Carbon\Exceptions\InvalidDateException;
use Cms\Classes\ComponentBase;
use GodSpeed\FlametreeCMS\Models\Event;
use GodSpeed\FlametreeCMS\Plugin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use October\Rain\Database\Relations\BelongsToMany;

class Events extends ComponentBase
{
    /**
     * @var
     */
    public $events;
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

        $events = $this->getEventsQuery()->get();
        $eventCollection = $this->makeEventsCollection($events);

        $this->events = $this->page['events'] = $eventCollection;
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
            return $member->groups()->with([
                'events' => function (BelongsToMany $query) use ($lowerBound, $upperBound) {
                    $query->whereBetween('started_at', [
                        $lowerBound->toDateString(),
                        $upperBound->toDateString()
                    ]);
                }
            ]);
        }
        return [];
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
        $groups = $member->groups()->with(['events'])->get();
        $eventID = collect();
        collect($groups)->each(function ($group) use ($eventID) {
            collect($group['events'])->each(function ($event) use ($eventID) {
                $eventID->push($event->id);
            });
        });

        $eventID = $eventID->unique()->toArray();
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
