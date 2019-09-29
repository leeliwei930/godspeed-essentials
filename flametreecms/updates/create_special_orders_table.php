<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSpecialOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_flametreecms_special_orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string("name");
            $table->string("email");
            $table->string("phone_number");
            $table->text("message");
            $table->boolean("is_read")->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('godspeed_flametreecms_special_orders');
    }
}
