<?php namespace GodSpeed\FlametreeCMS\Updates;

use GodSpeed\FlametreeCMS\Models\event;

use Seeder;

class EventSeeder extends Seeder
{

    public function run()
    {

        factory(Event::class, 50)->create([
            'timezone' => "Australia/Sydney"
        ]);
    }
}
