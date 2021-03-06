<?php namespace GodSpeed\Essentials\Models;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Model;

/**
 * Referral Model
 */
class Referral extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_essentials_referrals';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'code', 'valid_before', 'valid_after', 'usage_left', 'timezone', 'capped', 'user_group_id'
    ];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [

    ];


    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [
        'integer' => 'usage_left',
        'boolean' => 'capped'
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
        'valid_before',
        'valid_after',
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'users' => [
            'RainLab\User\Models\User',
            'key' => 'godspeed_essentials_referral_id'
        ]
    ];
    public $belongsTo = [
        'user_group' => [
            'RainLab\User\Models\UserGroup'

        ]
    ];
    public $belongsToMany = [

    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function beforeValidate(){
        $rules = [
            'code' => [
                'required', 'unique:godspeed_essentials_referrals,code', 'between:3,255'
            ],
            'valid_before' => [
                'required',
                'date',
                'after:valid_after'
            ],
            'valid_after' => [
                'required',
                'date',
                'before:valid_before'
            ],
            'usage_left' => [
                'min:0',
                'numeric'
            ],
            'timezone' => [
                'required', Rule::in($this->getTimezoneOptions())
            ],
            'capped' => [
                'required', 'boolean'
            ],
            'user_group_id' => [
                'required', "exists:user_groups,id"
            ]
        ];
        $this->rules = $rules;
    }

    public function filterFields($fields, $context = null){
        if ($context === "create") {
            $fields->timezone->value = Settings::get('default_timezone', 'Australia/Sydney');
        }
    }
    public function getValidBeforeAttribute($date)
    {
        // if the system timezone is match with the record timezone, don't convert it
        if ($this->getSystemTimezone() === $this->timezone) {
            return $date;
        }

        // Get the system timezone and use the started at date parameters
        $validBeforeDate = Carbon::parse($date, $this->getSystemTimezone());

        // return to a converted date time based on the timezone
        return $validBeforeDate->setTimezone($this->timezone);
    }

    public function getValidAfterAttribute($date)
    {
        // if the system timezone is match with the record timezone, don't convert it
        if ($this->getSystemTimezone() === $this->timezone) {
            return $date;
        }

        // Get the system timezone and use the started at date parameters
        $validBeforeDate = Carbon::parse($date, $this->getSystemTimezone());

        // return to a converted date time based on the timezone
        return $validBeforeDate->setTimezone($this->timezone);
    }

    public function setValidBeforeAttribute($date)
    {

        // if the selected timezone is match with the system timezone, just save the record
        if ($this->getSystemTimezone() === $this->timezone) {
            $this->attributes['valid_before'] = $date;
            return;
        }
        // convert the time to UTC
        $startedAt = Carbon::parse($date, $this->timezone);
        $this->attributes['valid_before'] = $startedAt->setTimezone($this->getSystemTimezone())->toDateTimeString();
    }

    public function setValidAfterAttribute($date)
    {
        if ($this->getSystemTimezone() === $this->timezone) {
            $this->attributes['valid_after'] = $date;
            return;
        }
        // convert the time to UTC
        $startedAt = Carbon::parse($date, $this->timezone);
        $this->attributes['valid_after'] = $startedAt->setTimezone($this->getSystemTimezone())->toDateTimeString();
    }

    public function getSystemTimezone()
    {
        return \Config::get('cms.backendTimezone');
    }

    /**
     * Return a dropdown list of timezone into the event management form
     * @return array
     */
    public function getTimezoneOptions()
    {
        $timezones = collect(timezone_identifiers_list())->mapWithKeys(function ($value) {
            return [$value => $value];
        });
        unset($timezones['UTC']);

        return $timezones->toArray();
    }


    public static function findByCode($code)
    {

        return self::where('code', $code)->get()->first();
    }


    public function updateUsageLeft()
    {
        $updateUsageLimit = $this->usage_left - 1;
        if ($updateUsageLimit < 0) {
            $this->usage_left = 0;
        } else {
            $this->usage_left = $updateUsageLimit;
        }
        $this->save();

    }

    public function isExpired()
    {

        $start = Carbon::parse($this->valid_after);
        $end = Carbon::parse($this->valid_before);


        return !now($this->timezone)->isBetween(
            $start,
            $end

        );

    }


}
