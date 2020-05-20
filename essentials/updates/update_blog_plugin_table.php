<?php namespace GodSpeed\Essentials\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use System\Classes\PluginManager;

class UpdateBlogPlugin extends Migration
{
    public function up()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Blog')) {

            if (Schema::hasTable('rainlab_blog_categories')) {
                Schema::table('rainlab_blog_categories', function (Blueprint $table) {
                    $table->string('godspeed_essentials_featured_image')->nullable()->after('description');
                    $table->unsignedInteger('godspeed_essentials_user_group')->nullable()->after('id');
                });
            }
        }
    }

    public function down()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Blog')) {

            if (Schema::hasTable('rainlab_blog_categories')) {
                Schema::table('rainlab_blog_categories', function (Blueprint $table) {
                    if (Schema::hasColumn('rainlab_blog_categories', 'godspeed_essentials_featured_image')) {
                        $table->dropColumn('godspeed_essentials_featured_image');
                    }
                    if (Schema::hasColumn('rainlab_blog_categories', 'godspeed_essentials_user_group')) {
                        $table->dropColumn('godspeed_essentials_user_group');
                    }
                });
            }
        }

    }
}
