<?php

namespace GodSpeed\Essentials\Updates;

use RainLab\User\Models\User;
use RainLab\User\Models\UserGroup;
use RainLab\User\Facades\Auth;

class FrontendUserGroupSeeder extends \Seeder
{


    public function run()
    {

        $this->tearDown();
        $groups = [
            [
                'name' => "Volunteer",
                'code' => "volunteer",
                'users' => [

                        'name' => "Volunteer",
                        'surname' => "Dev",
                        'email' => 'volunteer@mail.com',
                        'password' => 'secret123',

                ]
            ],
            [
                'name' => "Member",
                'code' => "member",
                'users' => [

                        'name' => "Member",
                        'surname' => "Dev",
                        'email' => "member@mail.com",
                        'password' => 'secret123',

                ]
            ]
        ];

        collect($groups)->each(function ($group) use ($groups) {
            $_group = UserGroup::firstOrCreate(['name' => $group['name'] , 'code' => $group['code']]);
            $group['users']['password_confirmation'] = $group['users']['password'];
            $testUser = User::where('email', $group['users']['email'])->get();
            if ($testUser->count()  == 0) {
                $user = Auth::register($group['users'], true);
                $user->addGroup($_group->id);

            }
        });
    }


    public function tearDown()
    {
        if (!env('production')) {
            $volunteer = UserGroup::where('code', 'volunteer')->first();
            $member = UserGroup::where('code', 'member')->first();

            if (!is_null($volunteer)) {
                $volunteer->users()->each(function ($user) {
                    $user->forceDelete();
                });
            }
            if (!is_null($member)) {
                $member->users()->each(function ($user) {
                    $user->forceDelete();
                });
            }
        }
    }
}
