<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use System\Classes\PluginManager;

class UpdateBlogPlugin extends Migration
{
    public function up()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Blog')) {
            Schema::table('rainlab_blog_categories', function (Blueprint $table) {
                $table->string('featured_image')->nullable()->after('description');
                $table->unsignedInteger('user_group')->nullable()->after('id');
            });
        }
    }

    public function down()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Blog')) {
            Schema::table('rainlab_blog_categories', function (Blueprint $table) {
                $table->dropColumn([
                    'featured_image',
                    'user_group',
                ]);
            });
        }
    }
}
