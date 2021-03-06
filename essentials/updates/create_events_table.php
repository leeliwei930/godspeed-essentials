<?php namespace GodSpeed\Essentials\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateMeetingsTable extends Migration
{
    public function up()
    {
        Schema::create('godspeed_essentials_events', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->text('location')->nullable();

            $table->string('timezone');
            $table->longText('content_html')->nullable();
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->timestamps();
        });

        Schema::create('godspeed_essentials_events_roles', function (Blueprint $table) {
            $table->unsignedInteger('member_role_id');
            $table->unsignedInteger('event_id');

            $table->primary(['member_role_id', 'event_id'], 'godspeed_essentials_pk_event_role');
            $table->foreign('event_id', 'godspeed_essentials_event_role')
                ->references('id')
                ->on('godspeed_essentials_events')
                ->onDelete('cascade');
//
//            $table->foreign('member_role_id', 'godspeed_essentials_role_event')
//                ->references('id')
//                ->on('user_groups')
//                ->onDelete('cascade');
        });
    }

    public function down()
    {
        if (Schema::hasTable('godspeed_essentials_events_roles')) {
            Schema::table('godspeed_essentials_events_roles', function (Blueprint $table) {
                $table->dropForeign('godspeed_essentials_event_role');
//                $table->dropForeign('godspeed_essentials_role_event');
            });
        }
        Schema::dropIfExists('godspeed_essentials_events_roles');
        Schema::dropIfExists('godspeed_essentials_events');
    }
}
