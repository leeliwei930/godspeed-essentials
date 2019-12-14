<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_flametreecms_producers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('origin');
            $table->string('website')->nullable();
            $table->string('logo')->default('default.png');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('godspeed_flametreecms_producers');
    }
}
