<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomdata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roomdata', function (Blueprint $table) {
            $table->increments('RoomdataId');
            
            $table->integer('ID')->comment('场次')->nullable();
            $table->string('name')->comment('场次名称')->nullable();
            $table->integer('min')->comment('场次进入最小限制')->nullable();
            $table->integer('max')->comment('场次进入最大限制')->nullable();
            $table->integer('cost')->comment('台费')->nullable();
            $table->integer('dizhu')->comment('底注')->nullable();
            $table->integer('dingzhu')->comment('顶注')->nullable();
            $table->integer('max_look_round')->comment('最大看牌轮数')->nullable();
            $table->integer('comparable_bet_round')->comment('最大可比轮数')->nullable();
            $table->integer('max_bet_round')->comment('可比轮数')->nullable();
            $table->string('img_bg')->comment('底图名字')->nullable();
            $table->string('img_icon')->comment('场次标识图片名字')->nullable();
            $table->boolean('open_robot')->comment('是否开放机器人')->nullable();

            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roomdata');
    }
}
