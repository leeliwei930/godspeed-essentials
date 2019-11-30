<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Illuminate\Support\Facades\DB;
class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_flametreecms_videos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('embed_id');
            $table->string('type');
            $table->unsignedInteger("video_playlist_id")->nullable();
            $table->timestamps();


            $table->foreign('video_playlist_id')
                ->references('id')
                ->on('godspeed_flametreecms_video_playlists')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('godspeed_flametreecms_videos');
    }
}
