<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateBlogPlugin extends Migration
{
    public function up()
    {
        Schema::table('rainlab_blog_categories', function (Blueprint $table) {
            $table->string('featured_image')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('rainlab_blog_categories' , function(Blueprint $table){
            $table->dropColumn([
                'featured_image'
            ]);
        });
    }
}
