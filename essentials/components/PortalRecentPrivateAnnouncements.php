<?php namespace GodSpeed\Essentials\Components;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Cms\Classes\ComponentBase;
use October\Rain\Database\Builder;
use RainLab\Blog\Models\Post as BlogPost;
use Auth;

class PortalRecentPrivateAnnouncements extends ComponentBase
{

    /**
     * @var
     */
    public $announcements = [];

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'Recent Private Announcements',
            'description' => 'Load a collection of recent private announcement based on given parameters'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        return [
            'duration' => [
                'title' => "Recent value",
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'default' => "7"
            ],

            'duration_unit' => [
                'title' => "Duration Unit",
                'type' => 'dropdown',
                'options' => [
                    "days" => "Days",
                    "weeks" => "Weeks",
                    "months" => "Months",
                    "year" => "Year"
                ],
                'default' => "days"
            ],

            'max_records' => [
                'title' => "Max num of records",
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'default' => "5"
            ]

        ];
    }

    /**
     *
     */
    public function onRun()
    {
        $this->announcements  = $this->retrieveAnnouncements();
    }

    /**
     * @return mixed
     */
    public function retrieveAnnouncements()
    {
        $groups = optional(Auth::user())->groups()->pluck('id') ?? [];

        $unit = $this->property('duration_unit');
        $carbonInterval = CarbonInterval::$unit($this->property('duration'));
        $fromDate = now()->sub($carbonInterval)->toDateTimeString();
        $toDate = now()->toDateTimeString();


        $posts = BlogPost::whereHas('categories', function (Builder $query) use ($groups) {
            $query = $query->whereNotNull('user_group');
            if (count($groups) > 0) {
                $query->whereIn('user_group', $groups);
            }
        })
        ->whereDate('published_at', ">=", $fromDate)
        ->whereDate('published_at', "<=", $toDate)->limit($this->property('max_records'));

        return $posts->get();
    }

    /**
     * @return string
     */
    public function getPanelHeaderTitle()
    {
        return "Recent " . $this->property('duration') . " " . $this->property('duration_unit') . " announcements";
    }
}


