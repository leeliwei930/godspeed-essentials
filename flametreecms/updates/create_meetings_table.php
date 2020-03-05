<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateMeetingsTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_flametreecms_meetings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->longText('content_html')->nullable();
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('godspeed_flametreecms_meetings');
    }
}
