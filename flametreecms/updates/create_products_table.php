<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_flametreecms_products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('producer_name');
            $table->string('producer_origin');
            $table->string('producer_website')->nullable();
            $table->string('producer_logo')->default('default.png');
            $table->unsignedInteger('product_category_id')->nullable();

            $table->foreign('product_category_id')->references('id')->on('godspeed_flametreecms_product_categories')->onDelete('set null');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('godspeed_flametreecms_products');
    }
}
