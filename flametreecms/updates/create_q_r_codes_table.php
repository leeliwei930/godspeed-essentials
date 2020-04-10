<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateQRCodesTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_flametreecms_q_r_codes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('resource_name');
            $table->string('page');
            $table->json('slugs');
            $table->json('fields');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('godspeed_flametreecms_q_r_codes');
    }
}
