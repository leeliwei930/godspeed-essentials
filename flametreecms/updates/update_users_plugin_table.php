<?php namespace GodSpeed\FlametreeCMS\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateUsersPluginTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
        });
    }

    public function down()
    {
       Schema::table('users' , function(Blueprint $table){
           $table->dropColumn([
               'phone_number'
           ]);
       });
    }
}
