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
    /**
     * @var
     */
    public $eventPage;
    /**
     * @var
     */
    public $timelines;
    /**
     * @var
     */
    public $selectedTimeLine;

    public $eventPageSlug;
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
            ],
            'event_page_slug' => [
                'title' => "Event Page Slug",
                'type' => 'string',
                'default' => "slug"
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
        $this->events = $this->page['events'] = $this->getEventsQuery();
        $this->eventPage = $this->page['eventPage'] = $this->property('event_page');
        $this->eventPageSlug = $this->page['eventPageSlug'] = $this->property('event_page_slug');
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
            return Event::userGroupBetween($lowerBound, $upperBound)->get();
        } else {
            return Event::publicBetween($lowerBound, $upperBound)->get();
        }
    }

    /**
     * Checking on the first day of the month specify in the url, if not system date will be used.
     * @return mixed|string
     */
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


    /**
     * Aggregate the events to month-year group
     * @return mixed
     */
    public function getTimelineLabel()
    {
        $member = $this->getCurrentMemberSession();
        $eventID = collect();

        if (!is_null($member)) {
            $events = Event::userGroup();
            $eventID = $events->pluck('id');
        } else {
            $events = Event::public();

            $eventID = $events->pluck('id');
        }

        $eventID = $eventID->toArray();


        return Event::selectRaw('DATE_FORMAT(started_at, "%M, %Y") as timeline_label, DATE_FORMAT(started_at, "%m-%Y") as timeline_value, COUNT(*) as count')
            ->whereIn('id', $eventID)
            ->groupBy('timeline_label', 'timeline_value')
            ->orderByRaw("MIN(started_at) desc")
            ->get();
    }

    /**
     * Return the default month name query parameter
     * @return string
     */
    private function getDefaultMonthNameField()
    {
        return 'scope';
    }


    /**
     * Get the monthname query parameter value
     * @return string|null
     */
    public function getMonthNameKey()
    {
        return $this->property('monthname_field');
    }


    public function getEventPageUrl($slug){
        return Page::url($this->eventPage, [$this->eventPageSlug => $slug]);
    }
}
