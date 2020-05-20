<?php namespace GodSpeed\Essentials\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use System\Classes\PluginManager;

class CreateReferralsTable extends Migration
{
    public function up()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.User')) {

            Schema::create('godspeed_essentials_referrals', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('code')->unique();
                $table->string('timezone');
                $table->timestamp('valid_before')->nullable();
                $table->timestamp('valid_after')->nullable();
                $table->unsignedInteger('usage_left');
                $table->unsignedInteger('user_group_id');

//                $table->foreign('user_group_id')->references('id')->on('user_groups')->onDelete('cascade');
                $table->boolean('capped')->default(false);
                $table->timestamps();
            });

        }
        if (PluginManager::instance()->hasPlugin('RainLab.User')) {

            if (Schema::hasTable('users')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->unsignedInteger('godspeed_essentials_referral_id')->nullable();

                });
            }
        }
    }

    public function down()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.User')) {
            if (Schema::hasTable('users')) {
                Schema::table('users', function (Blueprint $table) {
                    if (Schema::hasColumn('users', 'godspeed_essentials_referral_id')) {
                        $table->dropColumn('godspeed_essentials_referral_id');
                    }
                });
            }
        }
//        if(Schema::hasTable('godspeed_essentials_referrals')){
//            Schema::table('godspeed_essentials_referrals', function(Blueprint $table) {
//                $table->dropForeign(['user_group_id']);
//            });
//        }
        Schema::dropIfExists('godspeed_essentials_referrals');
    }
}
