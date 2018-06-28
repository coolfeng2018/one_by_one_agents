<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorseMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horse_message', function (Blueprint $table) {
            $table->increments('HorseMessageId');

            $table->integer('ID')->comment('广播ID')->nullable();
            $table->string('content')->comment('广播内容')->nullable();
            $table->integer('min_time')->comment('最小间隔')->nullable();
            $table->integer('max_time')->comment('最大间隔')->nullable();
            
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
        Schema::drop('horse_message');
    }
}
