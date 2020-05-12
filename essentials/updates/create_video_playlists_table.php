<?php namespace GodSpeed\Essentials\Updates;


use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVideoPlaylistsTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_essentials_playlists', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create("godspeed_essentials_video_playlists" , function(Blueprint $table){
            $table->unsignedInteger('video_id');
            $table->unsignedInteger('playlist_id');

            $table->foreign('video_id' , "godspeed_essentials_vID_fg")
                  ->references('id')
                  ->on('godspeed_essentials_videos')
                  ->onDelete('cascade');

            $table->foreign('playlist_id' , "godspeed_essentials_pID_fg")
                    ->references('id')
                    ->on('godspeed_essentials_playlists')
                    ->onDelete('cascade');

            $table->primary(['video_id', 'playlist_id'], "godspeed_essentials_VID_PID_PK");
        });

    }

    public function down()
    {
        if(Schema::hasTable('godspeed_essentials_video_playlists')){
            Schema::table('godspeed_essentials_video_playlists', function(Blueprint $table){
                $table->dropForeign('godspeed_essentials_vID_fg');
                $table->dropForeign('godspeed_essentials_pID_fg');
            });
        }
        Schema::dropIfExists("godspeed_essentials_video_playlists");
        Schema::dropIfExists('godspeed_essentials_playlists');

    }
}
