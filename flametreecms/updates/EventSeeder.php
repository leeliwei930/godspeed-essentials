<?php namespace GodSpeed\FlametreeCMS\Updates;

use GodSpeed\FlametreeCMS\Models\Event;

use RainLab\User\Models\UserGroup;
use Seeder;

class EventSeeder extends Seeder
{

    public function run()
    {
        if (env('APP_ENV') === 'acceptance') {
            return;
        }
        $events  = factory(Event::class, 50)->create([
            'timezone' => "Australia/Sydney"
        ]);

        $volunteerRole = UserGroup::where('code', 'volunteer')->first();
        collect($events)->each(function ($event) use ($volunteerRole) {
            $event->user_group()->attach([$volunteerRole->id]);
        });
    }
}
