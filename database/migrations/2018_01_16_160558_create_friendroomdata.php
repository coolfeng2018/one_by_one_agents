<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendroomdata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friendroomdata', function (Blueprint $table) {
            $table->increments('FriendRoomdataId');
            
            $table->integer('table_type')->comment('场次')->nullable();
            $table->string('name')->comment('场次名称')->nullable();
            $table->boolean('is_club')->comment('是否俱乐部玩法 1true 2false')->nullable();
            $table->integer('cost_type')->comment('房间内消耗')->nullable();
            $table->integer('cost')->comment('消耗')->nullable();
            $table->integer('aa_cost')->comment('AA消耗')->nullable();
            $table->integer('max_count')->comment('最大局数')->nullable();
            $table->string('play_num')->comment('游戏人数')->nullable();
            $table->integer('min_dizhu')->comment('最小底分')->nullable();
            $table->integer('max_dizhu')->comment('最大底分')->nullable();
            $table->integer('min_white_dizhu')->comment('白名单最小底分')->nullable();
            $table->integer('max_white_dizhu')->comment('白名单最大底分')->nullable();
            $table->integer('min_ration')->comment('单注最小倍数')->nullable();
            $table->integer('max_ration')->comment('单注最大倍数')->nullable();
            $table->integer('comparable_bet_round')->comment('最大可比轮数')->nullable();
            $table->integer('max_bet_round')->comment('可比轮数')->nullable();
            $table->integer('max_look_round')->comment('最大可看轮数')->nullable();
            $table->integer('max_need_money')->comment('最大携带')->nullable();
            $table->string('white_list')->comment('白名单')->nullable();
            $table->double('ration', 50, 1)->comment('台费配置系数')->nullable();

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
        Schema::drop('friendroomdata');
    }
}
