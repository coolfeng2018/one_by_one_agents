<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_list', function (Blueprint $table) {
            $table->increments('GameListId');

            $table->integer('id')->comment('连续签到天数');
            $table->integer('game_type')->comment('游戏类型');
            $table->string('name')->comment('游戏名');
            $table->string('icon')->comment('显示图标')->nullable();
            $table->integer('shown_type')->comment('类型【1主界面】【2私人房】【3快速开始】')->nullable();
            $table->integer('status')->comment('状态【0正常】【1敬请期待】')->nullable();
            
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
        Schema::drop('game_list');
    }
}
