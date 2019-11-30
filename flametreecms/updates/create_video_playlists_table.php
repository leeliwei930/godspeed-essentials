<?php namespace GodSpeed\FlametreeCMS\Updates;

use GodSpeed\FlametreeCMS\Models\VideoPlaylist;
use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVideoPlaylistsTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_flametreecms_video_playlists', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('godspeed_flametreecms_video_playlists');
    }
}
