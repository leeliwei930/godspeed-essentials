<?php namespace GodSpeed\Essentials\Updates;

use Cms\Classes\Theme;
use GodSpeed\Essentials\Models\Event;

use RainLab\User\Models\UserGroup;
use Seeder;

class EventSeeder extends Seeder
{

    public function run()
    {
        if (env('APP_ENV') === 'acceptance') {
            return;
        }
        if(Theme::getActiveThemeCode() !== 'flametree-theme'){
            return;
        }
        $events  = factory(Event::class, 10)->create([
            'timezone' => "Australia/Sydney"
        ]);

        $volunteerRole = UserGroup::where('code', 'volunteer')->first();
        collect($events)->each(function ($event) use ($volunteerRole) {
            $event->user_group()->attach([$volunteerRole->id]);
        });
    }
}
