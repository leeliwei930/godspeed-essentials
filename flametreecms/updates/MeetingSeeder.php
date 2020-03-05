<?php namespace GodSpeed\FlametreeCMS\Updates;

use GodSpeed\FlametreeCMS\Models\Meeting;

use Seeder;

class MeetingSeeder extends Seeder
{

    public function run()
    {

        factory(Meeting::class, 50)->create();

    }




}
