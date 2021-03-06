<?php namespace GodSpeed\Essentials\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use System\Classes\PluginManager;

class UpdateUsersPluginTable extends Migration
{
    public function up()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.User')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('godspeed_essentials_phone_number')->nullable()->after('email');
            });
        }
    }

    public function down()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.User')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'godspeed_essentials_phone_number')) {
                    $table->dropColumn([
                        'godspeed_essentials_phone_number'
                    ]);
                }
            });
        }
    }
}
