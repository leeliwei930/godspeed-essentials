<?php namespace GodSpeed\FlametreeCMS\Models;

use Carbon\Carbon;
use Cms\Classes\Page;
use Model;
use October\Rain\Database\Attach\File;

/**
 * Meeting Model
 */
class Event extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_flametreecms_events';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'title', 'description', 'started_at', 'ended_at', 'content_html', 'location', 'timezone'
    ];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'title' => [
            'required',
            'between:5,255',
            'unique:godspeed_flametreecms_events,title'
        ],
        'slug' => [
            'required',
            'between:5,255'
        ],
        'description' => [
            'max:65535'
        ],
        'started_at' => [
            'required',
            'date',
            'before:ended_at'
        ],
        'ended_at' => [
            'required',
            'date',
            'after:started_at'
        ]
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [
        'started_at' => "Y-m-d H:i",
        'ended_at' => "Y-m-d H:i"
    ];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'started_at',
        'ended_at',
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    /**
     * @var array
     */
    public $hasMany = [];
    /**
     * @var array
     */
    public $belongsTo = [];
    /**
     * @var array
     */
    public $belongsToMany = [
        'user_group' => [
            'RainLab\User\Models\UserGroup' ,
            'table' => 'godspeed_flametreecms_events_roles',
            'otherKey' => 'member_role_id'
        ]
    ];
    /**
     * @var array
     */
    public $morphTo = [];
    /**
     * @var array
     */
    public $morphOne = [];
    /**
     * @var array
     */
    public $morphMany = [];
    /**
     * @var array
     */
    public $attachOne = [
        'ics' => [
            'System\Models\File'
        ]
    ];
    /**
     * @var array
     */
    public $attachMany = [

        'documents' => [
            'System\Models\File'
        ]
    ];

    /**
     * @return array
     */



    public function afterValidate()
    {

        if (!$this->hasCreatedICSFileBefore()) {
            $icalEvent = $this->makeiCalEvent();
            $filename =str_limit($this->slug, 10, '').'.ics';
            $file =  new \System\Models\File();
            $file->fromData($this->makeCalendarInstance($icalEvent)->render(), $filename);
            $file->save();
            $this->ics = $file;
            $this->forceSave();
        } else {
            $icalEvent = $this->makeiCalEvent();
            $filename = str_limit($this->slug, 10, '').'.ics';
            $this->ics->fromData($this->makeCalendarInstance($icalEvent)->render(), $filename);
            $this->ics->save();
        }
    }

    public function hasCreatedICSFileBefore()
    {
        return $this->ics !== null;
    }

    private function makeCalendarInstance($event)
    {

        $calendar = new \Eluceo\iCal\Component\Calendar($this->title);
        $calendar->addComponent($event);
        return $calendar;
    }

    private function makeiCalEvent()
    {
        $event = new \Eluceo\iCal\Component\Event();
        $event->setTimezoneString($this->timezone);
        $event->setDtStart($this->started_at);
        $event->setDtEnd($this->ended_at);
        $event->setSummary($this->title);
        return $event;
    }

    public function getTimezoneOptions()
    {
        $timezones = collect(timezone_identifiers_list())->mapWithKeys(function ($value) {
            return [$value => $value];
        });
        return $timezones->toArray();
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getSystemTimezone()
    {
        return config('app.timezone');
    }

    /**
     * @param $fields
     * @param null $context
     */

    public function filterFields($fields, $context = null)
    {
        if ($context === "create") {
            $fields->timezone->value = $this->getSystemTimezone();
        }
    }





    /**
     * @param $date
     * @return \DateTime
     */
    public function getStartedAtAttribute($date)
    {

        if ($this->getSystemTimezone() === $this->timezone) {
            return $date;
        }


        $startedAt = Carbon::parse($date, $this->getSystemTimezone());

        return $startedAt->setTimezone($this->timezone);
    }

    /**
     * @param $date
     */
    public function setStartedAtAttribute($date)
    {

        if ($this->getSystemTimezone() === $this->timezone) {
            $this->attributes['started_at'] = $date;
            return;
        }
        $startedAt = Carbon::parse($date, $this->timezone);
        $this->attributes['started_at'] = $startedAt->setTimezone($this->getSystemTimezone())->toDateTimeString();
    }

    /**
     * @param $date
     * @return \DateTime
     */
    public function getEndedAtAttribute($date)
    {
        if ($this->getSystemTimezone() === $this->timezone) {
            return $date;
        }

        $endedAt = Carbon::parse($date, $this->getSystemTimezone());

        return $endedAt->setTimezone($this->timezone);
    }

    /**
     * @param $date
     */
    public function setEndedAtAttribute($date)
    {
        if ($this->getSystemTimezone() === $this->timezone) {
            $this->attributes['ended_at'] = $date;
            return;
        }
        $endedAt = Carbon::parse($date, $this->timezone);
        $this->attributes['ended_at'] = $endedAt->setTimezone($this->getSystemTimezone())->toDateTimeString();
    }
}
