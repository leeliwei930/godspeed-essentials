<?php namespace GodSpeed\Essentials\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_essentials_videos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('embed_id');
            $table->string('type');
            $table->integer('duration')->default(0);
            $table->string('featured_image')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();

        });

    }

    public function down()
    {
        Schema::dropIfExists('godspeed_essentials_videos');
    }
}
