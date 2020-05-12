<?php namespace GodSpeed\Essentials\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateProductCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_essentials_product_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->timestamps();
        });

        Schema::create("godspeed_essentials_products_categories", function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('product_category_id');

            $table->foreign('product_id', "godspeed_essentials_product_products_categories_fk")
                ->references('id')
                ->on('godspeed_essentials_products')
                ->onDelete('cascade');
            $table->foreign('product_category_id', "godspeed_essentials_product_categories_products_fk")
                ->references('id')
                ->on('godspeed_essentials_product_categories')
                ->onDelete('cascade');
            $table->primary(['product_id', 'product_category_id'], "godspeed_essentials_products_categories_pk");
        });
    }

    public function down()
    {
        if (Schema::hasTable('godspeed_essentials_products_categories')) {
            Schema::table('godspeed_essentials_products_categories', function (Blueprint $table) {
                $table->dropForeign('godspeed_essentials_product_products_categories_fk');
                $table->dropForeign('godspeed_essentials_product_categories_products_fk');
            });
        }
        Schema::dropIfExists('godspeed_essentials_products_categories');
        Schema::dropIfExists('godspeed_essentials_product_categories');
    }
}
