<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateProductCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_flametreecms_producer_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('godspeed_flametreecms_producer_categories_pivot', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('producer_id');
            $table->unsignedInteger('producer_category_id');

            $table->primary(['producer_id', 'producer_category_id'], "godspeed_flametreecms_pk_producer_category");

            $table->foreign('producer_id', 'godspeed_flametreecms_fk_producer_category')
                    ->references('id')
                    ->on('godspeed_flametreecms_producers')
                    ->onDelete('cascade');
            $table->foreign('producer_category_id', 'godspeed_flametreecms_fk_category_producer')
                ->references('id')
                ->on('godspeed_flametreecms_producer_categories')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        if(Schema::hasTable('godspeed_flametreecms_producer_categories_pivot')) {
            Schema::table('godspeed_flametreecms_producer_categories_pivot', function (Blueprint $table) {
                $table->dropForeign('godspeed_flametreecms_fk_producer_category');
                $table->dropForeign('godspeed_flametreecms_fk_category_producer');
            });
        }
        Schema::dropIfExists('godspeed_flametreecms_producer_categories_pivot');
        Schema::dropIfExists('godspeed_flametreecms_producer_categories');

    }
}
