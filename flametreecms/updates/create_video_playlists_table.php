<?php namespace GodSpeed\FlametreeCMS\Updates;

use GodSpeed\FlametreeCMS\Models\Playlist;
use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVideoPlaylistsTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_flametreecms_playlists', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create("godspeed_flametreecms_video_playlists" , function(Blueprint $table){
            $table->unsignedInteger('video_id');
            $table->unsignedInteger('playlist_id');

            $table->foreign('video_id' , "godspeed_flametreecms_vID_fg")
                  ->references('id')
                  ->on('godspeed_flametreecms_videos')
                  ->onDelete('cascade');

            $table->foreign('playlist_id' , "godspeed_flametreecms_pID_fg")
                    ->references('id')
                    ->on('godspeed_flametreecms_playlists')
                    ->onDelete('cascade');

            $table->primary(['video_id', 'playlist_id'], "godspeed_flametreecms_VID_PID_PK");
        });

    }

    public function down()
    {
        Schema::dropIfExists("godspeed_flametreecms_video_playlists");
        Schema::dropIfExists('godspeed_flametreecms_playlists');

    }
}
