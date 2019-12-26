<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateImageSlidersTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_flametreecms_image_sliders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('label')->unique();
            $table->longText('slides')->nullable();
            $table->boolean('autoplay');
            $table->integer('interval')->nullable();
            $table->boolean('show_navigation');
            $table->boolean('responsive_size');
            $table->integer("size_w")->nullable();
            $table->integer("size_h")->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('godspeed_flametreecms_image_sliders');
    }
}
