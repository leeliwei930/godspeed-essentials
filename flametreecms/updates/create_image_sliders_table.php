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
            $table->string('prev_slide_animation');
            $table->string("next_slide_animation");
            $table->boolean('autoplay');
            $table->integer('interval')->nullable();
            $table->boolean('show_navigation');
            $table->string('navigation_prev_icon');
            $table->string('navigation_next_icon');
            $table->boolean('responsive_size');
            $table->integer("size_w")->nullable();
            $table->integer("size_h")->nullable();
            $table->string('navigation_control_shape');
            $table->boolean('autohide_navigation_control');
            $table->string('navigation_control_bg_color');
            $table->string('navigation_color');
            $table->boolean('show_indicator');
            $table->string('indicator_active_class');
            $table->string('indicator_inactive_class');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('godspeed_flametreecms_image_sliders');
    }
}
