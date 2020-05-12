<?php namespace GodSpeed\Essentials\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use System\Classes\PluginManager;

class CreateTrainingsTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_essentials_trainings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('title')->unique();
            $table->string('slug')->index();
            $table->longText('content_html');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('video_playlist_id')->nullable();
            $table->timestamps();


            $table->foreign('user_id', 'godspeed_essentials_training_backend_user')
                ->references('id')
                ->on('backend_users')
                ->onDelete('cascade');
        });

        Schema::table('godspeed_essentials_trainings', function (Blueprint $table) {
            $table->foreign('video_playlist_id')
                ->references('id')
                ->on('godspeed_essentials_playlists')
                ->onDelete('set null');
        });


        Schema::create('godspeed_essentials_roles_trainings', function (Blueprint $table) {
            $table->unsignedInteger('training_id');
            $table->unsignedInteger('role_id');

            $table->foreign('training_id', 'godspeed_essentials_training_roles')
                ->references('id')
                ->on('godspeed_essentials_trainings')
                ->onDelete('cascade');

            $table->foreign('role_id', 'godspeed_essentials_roles_training')
                ->references('id')
                ->on('user_groups')
                ->onDelete('cascade');
            $table->primary(['training_id', 'role_id'], 'godspeed_essentials_roles_trainings_pk');
        });
    }

    public function down()
    {
        if (Schema::hasTable('godspeed_essentials_roles_trainings')) {
            Schema::table('godspeed_essentials_roles_trainings', function (Blueprint $table) {
                $table->dropForeign('godspeed_essentials_training_roles');
                $table->dropForeign('godspeed_essentials_roles_training');
            });
        }
        Schema::dropIfExists('godspeed_essentials_roles_trainings');
        if (Schema::hasTable('godspeed_essentials_trainings')) {
            Schema::table('godspeed_essentials_trainings', function (Blueprint $table) {
                $table->dropForeign(['video_playlist_id']);
                $table->dropForeign('godspeed_essentials_training_backend_user');
            });
        }
        Schema::dropIfExists('godspeed_essentials_trainings');
    }
}
