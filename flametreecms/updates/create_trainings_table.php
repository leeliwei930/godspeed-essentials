<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTrainingsTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_flametreecms_trainings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('slug')->index();
            $table->longText('content_html');
            $table->unsignedInteger('video_playlist_id')->nullable();
            $table->timestamps();
        });

        Schema::table('godspeed_flametreecms_trainings', function (Blueprint $table) {
            $table->foreign('video_playlist_id')
                    ->references('id')
                    ->on('godspeed_flametreecms_playlists')
                    ->onDelete('set null');
        });
    }

    public function down()
    {

        Schema::dropIfExists('godspeed_flametreecms_trainings');
    }
}
