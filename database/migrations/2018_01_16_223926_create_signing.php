<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signing', function (Blueprint $table) {
            $table->increments('SigningId');

            $table->integer('month')->comment('连续签到天数');
            $table->string('awards_list')->comment('奖励对象1')->nullable();//array[dict[2]:2]
                // $table->string('awards_list')->comment('奖励对象1')->nullable();//dict[2]
                    // $table->integer('id')->comment('奖励ID')->nullable();
                    // $table->integer('count')->comment('奖励数量')->nullable();
                // $table->string('awards_list')->comment('奖励对象2')->nullable();//dict[2]
                    // $table->integer('id')->comment('奖励ID')->nullable();
                    // $table->integer('count')->comment('奖励数量')->nullable();
            
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
        Schema::drop('signing');
    }
}
