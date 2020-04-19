<?php namespace GodSpeed\FlametreeCMS\Updates;

use Backend\Models\User;
use Backend\Models\UserGroup;
use Backend\Models\UserRole;
use GodSpeed\FlametreeCMS\Models\Faq;
use GodSpeed\FlametreeCMS\Models\FaqCategory;
use GodSpeed\FlametreeCMS\Models\Playlist;
use GodSpeed\FlametreeCMS\Models\SpecialOrder;
use GodSpeed\FlametreeCMS\Models\Video;
use October\Rain\Support\Collection;
use RainLab\Blog\Models\Post;
use Seeder;

class ScaffoldTeamMember extends Seeder
{


    const GROUP = [
        'name' =>  "FlameTree Community Food Co-Op"
    ];
    const ROLES = [
        'volunteer' => [
            'name' => "Volunteer",
            'code' => "volunteer"
        ],
        'advertising-team' => [
            'name' => "Advertising Team",
            'code' => 'advertising-team'
        ],
        'members' => [
            'name' => "Members",
            'code' => 'members'
        ]
    ];
    public function run()
    {

        self::tearDown();

        $roles = self::ROLES;


        $groups = self::GROUP;


        // create user roles
        collect($roles)->each(function ($role) {
            UserRole::create($role);
        });

        $group = UserGroup::create($groups);

        self::generateBackendUser($roles['volunteer']['code'], $groups['name']);
        self::generateBackendUser($roles['volunteer']['code'], $groups['name']);
        self::generateBackendUser($roles['volunteer']['code'], $groups['name']);
        self::generateBackendUser($roles['volunteer']['code'], $groups['name']);
        self::generateBackendUser($roles['volunteer']['code'], $groups['name']);

        self::generateBackendUser($roles['members']['code'], $groups['name']);
        self::generateBackendUser($roles['members']['code'], $groups['name']);
        self::generateBackendUser($roles['members']['code'], $groups['name']);

        self::generateBackendUser($roles['advertising-team']['code'], $groups['name']);
        self::generateBackendUser($roles['advertising-team']['code'], $groups['name']);
    }

    private static function generateBackendUser($role, $group, $options = [
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'password' => null
    ])
    {
        $faker =  new \Faker\Generator();
        $faker->addProvider(new \Faker\Provider\en_US\Person($faker));
        $faker->addProvider(new \Faker\Provider\en_AU\Internet($faker));

        $user = new User();
        $user->first_name = $options['first_name'] ?? $faker->firstName;
        $user->last_name = $options['last_name'] ?? $faker->lastName;
        $user->login = $options['login'] ?? $faker->userName;
        $user->email = $options['email'] ?? $faker->safeEmail;
        $user->password = $options['password'] ?? 'secret';
        $user->password_confirmation = $options['password'] ?? 'secret';
        $user->activated_at = now()->toDateTimeString();
        $user->save();

        $user->role = self::getRole($role)->id;
        $user->save();
        $user->groups()->attach(self::getGroup($group)->id);


        return $user;
    }

    private static function getRole($roleCode)
    {
        return UserRole::where('code', $roleCode)->first();
    }

    private static function getGroup($groupName)
    {
        return UserGroup::where('name', $groupName)->first();
    }

private static function tearDown()
    {
        // delete backend user
        $group = UserGroup::with('users')->where('name', self::GROUP['name'])->first();

        if (!is_null($group)) {
            collect($group->users)->each(function ($user) {
                $user->forceDelete();
            });
        }
        // delete role


        collect(self::ROLES)->each(function ($role) {
            $role = UserRole::where('code', $role['code'])->first();
            if (!is_null($role)) {
                $role->delete();
            }
        });

        // delete group
        $group = UserGroup::where('name', self::GROUP['name'])->first();

        if (!is_null($group)) {
            $group->delete();
        }
    }
}
