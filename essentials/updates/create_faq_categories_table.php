<?php namespace GodSpeed\Essentials\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateFaqCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_essentials_faq_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('godspeed_essentials_faq_categories_pivot', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('faq_id');
            $table->unsignedInteger('faq_category_id');

            $table->primary(['faq_id', 'faq_category_id'], 'godspeed_essentials_faq_category_cpk');

            $table->foreign('faq_id', "godspeed_essentials_faq_fk")
                ->references('id')
                ->on('godspeed_essentials_faqs')
                ->onDelete('cascade');

            $table->foreign('faq_category_id', "godspeed_essentials_faq_category_fk")
                ->references('id')
                ->on('godspeed_essentials_faq_categories')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        if (Schema::hasTable('godspeed_essentials_faq_categories_pivot')) {
            Schema::table('godspeed_essentials_faq_categories_pivot', function (Blueprint $table) {
                $table->dropForeign('godspeed_essentials_faq_fk');
                $table->dropForeign('godspeed_essentials_faq_category_fk');
            });
        }
        Schema::dropIfExists('godspeed_essentials_faq_categories_pivot');
        Schema::dropIfExists('godspeed_essentials_faq_categories');
    }
}
