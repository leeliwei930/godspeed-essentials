<?php namespace GodSpeed\Essentials\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateFaqsTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_essentials_faqs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('question');
            $table->longText("answer");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('godspeed_essentials_faqs');
    }
}
