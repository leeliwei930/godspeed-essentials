<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use System\Classes\PluginManager;

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

            Schema::create('godspeed_flametreecms_roles_trainings', function (Blueprint $table) {
                $table->unsignedInteger('training_id');
                $table->unsignedInteger('role_id');

                $table->foreign('training_id', 'godspeed_flametreecms_training_roles')
                        ->references('id')
                        ->on('godspeed_flametreecms_trainings')
                        ->onDelete('cascade');

                $table->foreign('role_id', 'godspeed_flametreecms_roles_training')
                    ->references('id')
                    ->on('user_groups')
                    ->onDelete('cascade');
                $table->primary(['training_id', 'role_id'], 'godspeed_flametreecms_roles_trainings_pk');
            });
    }

    public function down()
    {
        Schema::dropIfExists('godspeed_flametreecms_roles_trainings');
        Schema::dropIfExists('godspeed_flametreecms_trainings');
    }
}
