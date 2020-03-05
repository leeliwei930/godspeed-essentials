<?php namespace GodSpeed\FlametreeCMS\Components;

use Carbon\CarbonInterval;
use Cms\Classes\ComponentBase;
use GodSpeed\FlametreeCMS\Models\Meeting;
use Illuminate\Database\Eloquent\Collection;


class Meetings extends ComponentBase
{
    /**
     * @var
     */
    public $meetings;
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Meetings',
            'description' => 'List all the meetings'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        return [
            'limit' => [
                'title' => "Maximum number of records",
                "description" => "0 as default, which is unlimited",
                'validationPattern' => '^[0-9]+$',
                'default' => "0"
            ],
            'show_upcoming' => [
                'title' => "Show Upcoming",
                'type' => "checkbox",
                'default' => "0"
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
                ]
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
        $this->meetings = $this->page['meetings'] = $this->fetchMeetings();
    }

    /**
     * Fetch meeting records.
     *
     * @return mixed
     */
    protected function fetchMeetings()
    {
        $hasLimit = $this->property('limit') > 0;
        $show_upcoming = $this->property('show_upcoming');


        // show upcoming meeting minutes
        if ($show_upcoming) {
            // get upcoming meeting query
            $query = $this->getUpcomingMeetingQuery();

            // if there is a limit
            if ($hasLimit) {
                // return limited result
                return $this->limitResult($query);
            }

            // return all upcoming meetings
            return $query->get();
        }

        //fetch all meetings
        $query = $this->getMeetingsQuery();

        if ($hasLimit) {
            // return limited result
            return $this->limitResult($query)->get();
        }

        // return all meetings
        return $query->get();
    }

    /**
     * Query that fetch all meetings
     * @return Meeting[]|Collection
     */
    private function getMeetingsQuery()
    {
        return Meeting::all();
    }

    /**
     * Query that grabs upcoming meetings
     * @return mixed
     */
    private function getUpcomingMeetingQuery()
    {
        return Meeting::whereDate('started_at', "<", $this->getMaxDateScope())
                ->whereDate('started_at', ">", now()->toDateString());
    }

    /**
     * Limit the query result
     * @param Collection $query
     * @return mixed
     */
    protected function limitResult(Collection $query)
    {
        $limit = $this->property('limit');

        return $query->limit($limit);
    }

    /**
     * Get the max scope of the event started date
     * @return \Illuminate\Support\Carbon
     */
    protected function getMaxDateScope()
    {
        $maxPeriod = $this->property('max_period');
        $period = $this->property('period');

        // prepare the interval, it will be called as CarbonInterval::months/days/weeks(value)
        $maxPeriodInterval = CarbonInterval::$period($maxPeriod);

        // grab the current time and add the interval to get the max scoped event start date.
        return now()->add($maxPeriodInterval);
    }
}



