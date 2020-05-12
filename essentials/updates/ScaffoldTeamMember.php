<?php namespace GodSpeed\Essentials\Updates;

use Backend\Models\User;
use Backend\Models\UserGroup;
use Backend\Models\UserRole;
use Backend\Models\UserRole as Role;
use Seeder;

class ScaffoldTeamMember extends Seeder
{


    const GROUP = [
        'name' => "FlameTree Community Food Co-Op"
    ];
    const ROLES = [
        'advertising-team' => [
            'name' => "Advertising Team",
            'code' => 'godspeed.essentials.advertising_team'
        ],
        'reviewer' => [
            'name' => "Reviewer",
            'code' => 'godspeed.essentials.reviewer'
        ],

        'editor' => [
            'name' => "Editor",
            'code' => 'godspeed.essentials.editor'
        ]
    ];

    public function run()
    {

        $this->tearDown();

        $roles = self::ROLES;


        $groups = self::GROUP;


        $roles = self::ROLES;
        collect($roles)->each(function ($role){
            $roleObj = Role::where('code' , $role['code']);
            if(!is_null($roleObj)){
                Role::create($role);
            }
        });
        $group = UserGroup::create($groups);


        $this->generateBackendUser($roles['advertising-team']['code'], $groups['name']);
        $this->generateBackendUser($roles['advertising-team']['code'], $groups['name']);
    }

    private function generateBackendUser($role, $group, $options = [
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'password' => null
    ])
    {
        $faker = new \Faker\Generator();
        $faker->addProvider(new \Faker\Provider\en_US\Person($faker));
        $faker->addProvider(new \Faker\Provider\en_AU\Internet($faker));

        $user = new User();
        $user->first_name = $options['first_name'] ?? $faker->unique()->firstName;
        $user->last_name = $options['last_name'] ?? $faker->unique()->lastName;
        $user->login = $options['login'] ?? $faker->unique()->userName;
        $user->email = $options['email'] ?? $faker->unique()->safeEmail;
        $user->password = $options['password'] ?? 'secret';
        $user->password_confirmation = $options['password'] ?? 'secret';
        $user->activated_at = now()->toDateTimeString();
        $user->save();

        $user->role = $this->getRole($role)->id;
        $user->save();
        $user->groups()->attach($this->getGroup($group)->id);


        return $user;
    }

    private function getRole($roleCode)
    {
        return UserRole::where('code', $roleCode)->first();
    }

    private function getGroup($groupName)
    {
        return UserGroup::where('name', $groupName)->first();
    }

    private function tearDown()
    {
        // delete backend user
        $group = UserGroup::with('users')->where('name', self::GROUP['name'])->first();
        if (!is_null($group)) {
            collect($group->users)->each(function ($user) {
                $user->forceDelete();
            });
            $group->forceDelete();
        }
        // delete role

        $roles = self::ROLES;
        collect($roles)->each(function ($role){
            $roleObj = Role::where('code' , $role['code']);
            if(!is_null($roleObj)){
                $roleObj->delete();
            }
        });

    }
}
