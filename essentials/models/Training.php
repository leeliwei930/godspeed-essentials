<?php namespace GodSpeed\Essentials\Models;

use BackendAuth;
use Model;
use GodSpeed\Essentials\Models\Playlist;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\UserGroup;

/**
 * Training Model
 */
class Training extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'godspeed_essentials_trainings';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['title', 'content_html', 'video_playlist_id'];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'title' => [
            'required',
            'between:5,255',
        ],
        'slug' => [
            'required',
            'between:5,255'
        ],
        'video_playlist_id' => [
            'nullable', 'exists:godspeed_essentials_playlists,id'
        ]
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

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
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [

    ];
    public $hasMany = [];
    public $belongsTo = [
        'video_playlist' => [
            \GodSpeed\Essentials\Models\Playlist::class
        ],
        'user' => [
            \Backend\Models\User::class
        ]
    ];
    public $belongsToMany = [
        'user_group' => [
            'RainLab\User\Models\UserGroup',
            'table' => "godspeed_essentials_roles_trainings",
            'otherKey' => 'role_id'

        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [
        'documents' => [
            'System\Models\File'
        ]
    ];

    public function getVideoPlaylistOptions()
    {
        return Playlist::all()->pluck('name', 'id');
    }

    public function beforeSave()
    {
        if ($user = BackendAuth::getUser()) {
            $this->user = $user;
        }
    }

    public function scopeFindUserTrainingBySlug($slug)
    {
        $groups = optional(Auth::user())->groups()->pluck('code');
        return self::whereHas('user_group', function ($query) use ($groups) {
            $query->whereIn('code', $groups)->orWhere('code', 'guest');
        })->orWhereDoesntHave('user_group')
        ->where('slug', $slug)
        ->first();
    }

    public function scopeUserGroupTraining($query)
    {
        $user = Auth::user();

        if (is_null($user)) {
            $groups = UserGroup::where('code', 'guest')->pluck('code');
            return $query->whereHas('user_group', function ($query) use ($groups) {
                $query->whereIn('code', $groups);
            })
            ->orWhereDoesntHave('user_group')
            ->with('documents', 'user_group', 'video_playlist.videos');
        }
        $groups = optional($user)->groups()->pluck('code');

        return $query->whereHas('user_group', function ($query) use ($groups) {
            $query->whereIn('code', $groups)->orWhere('code', 'guest');
        })->orWhereDoesntHave('user_group')
        ->with('documents', 'user_group', 'video_playlist.videos');
    }



    public function scopeUserGroup()
    {
        $groups = optional(Auth::user())->groups()->pluck('code');
        return self::whereHas('user_group', function ($query) use ($groups) {
            $query->whereIn('code', $groups)
                ->orWhere('code', 'guest');
        })
            ->orWhereDoesntHave('user_group')
            ->orderBy('created_at', 'desc');
    }

    public function scopePublic()
    {
        return self::whereHas('user_group', function ($query) {
            $query->where('code', 'guest');
        })
            ->orWhereDoesntHave('user_group')
            ->orderBy('created_at', 'desc');
    }
}
