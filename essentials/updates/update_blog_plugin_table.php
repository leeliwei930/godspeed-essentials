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
                    $table->string('featured_image')->nullable()->after('description');
                    $table->unsignedInteger('user_group')->nullable()->after('id');
                });
            }
        }
    }

    public function down()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Blog')) {

            if (Schema::hasTable('rainlab_blog_categories')) {
                Schema::table('rainlab_blog_categories', function (Blueprint $table) {
                    if (Schema::hasColumn('rainlab_blog_categories', 'featured_image')) {
                        $table->dropColumn('featured_image');
                    }
                    if (Schema::hasColumn('rainlab_blog_categories', 'user_group')) {
                        $table->dropColumn('user_group');
                    }
                });
            }
        }

    }
}
