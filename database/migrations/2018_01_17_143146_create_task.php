<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //类型数值解释？
        Schema::create('task', function (Blueprint $table) {
            $table->increments('TaskId');

            $table->integer('id')->comment('任务id');
            $table->integer('type')->comment('任务类型')->nullable();
            $table->integer('param')->comment('参数')->nullable();
            $table->integer('game_type')->comment('游戏类型');
            $table->integer('cycle')->comment('周期')->nullable();//注：cycle 1:日常任务2：周常任务3：月常任务
            $table->integer('process')->comment('总体进度')->nullable();
            $table->string('name')->comment('任务名称')->nullable();
            $table->string('award_list')->comment('奖励列表')->nullable();
                // $table->string('')->comment('奖励对象1')->nullable();//dict[2]
                    // $table->integer('id')->comment('奖励ID')->nullable();
                    // $table->integer('count')->comment('奖励数量')->nullable();
                    // $table->integer('next_id')->comment('下一个任务id')->nullable();
            $table->integer('next_id')->comment('下一个任务id')->nullable();
            
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
        Schema::drop('task');
    }
}
