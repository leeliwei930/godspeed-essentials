<?php namespace GodSpeed\Essentials\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateProducersTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_essentials_producers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('origin');
            $table->string('website')->nullable();
            $table->string('featured_image')->default('default.png');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('godspeed_essentials_producers');
    }
}
