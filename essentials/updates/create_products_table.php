<?php namespace GodSpeed\Essentials\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_essentials_products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->double('price');
            $table->longText('description')->nullable();
            $table->string('slug')->unique();
            $table->string('currency');
            $table->enum('type', ["service", "product"]);
            $table->boolean('has_stock_limit');
            $table->unsignedInteger('stock_left');
            $table->enum('billing_cycle', ["daily", "weekly", "monthly", "annually"]);
            $table->json('features')->nullable();
            $table->json('images')->nullable();
            $table->unsignedInteger('video_playlist_id')->nullable();
            $table->unsignedInteger('producer_id')->nullable();
            $table->boolean('is_active');
            $table->timestamps();

            $table->foreign('video_playlist_id', 'godspeed_essentials_products_video_fk')
                ->references('id')
                ->on('godspeed_essentials_playlists')
                ->onDelete('set null');

            $table->foreign('producer_id', 'godspeed_essentials_products_producer_fk')
                    ->references('id')
                    ->on('godspeed_essentials_producers')
                    ->onDelete('set null');
        });
    }

    public function down()
    {
        if (Schema::hasTable('godspeed_essentials_products') &&
            Schema::hasColumn('godspeed_essentials_products', 'video_playlist_id')
        ) {
            Schema::table('godspeed_essentials_products', function (Blueprint $table) {
                $table->dropForeign('godspeed_essentials_products_video_fk');
            });
        }
        if (Schema::hasTable('godspeed_essentials_products') &&
            Schema::hasColumn('godspeed_essentials_products', 'producer_id')
        ) {
            Schema::table('godspeed_essentials_products', function (Blueprint $table) {
                $table->dropForeign('godspeed_essentials_products_producer_fk');
            });
        }
        Schema::dropIfExists('godspeed_essentials_products');
    }
}
