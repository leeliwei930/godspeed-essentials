<?php

namespace GodSpeed\FlametreeCMS\Updates;

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

        collect($groups)->each(function ($group) {
            $_group = UserGroup::create(['name' => $group['name'] , 'code' => $group['code']]);
            $group['users']['password_confirmation'] = $group['users']['password'];
            $user = Auth::register($group['users'], true);
            $user->addGroup($_group->id);
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
                $volunteer->delete();
            }
            if (!is_null($member)) {
                $member->users()->each(function ($user) {
                    $user->forceDelete();
                });

                $member->delete();
            }
        }
    }
}
