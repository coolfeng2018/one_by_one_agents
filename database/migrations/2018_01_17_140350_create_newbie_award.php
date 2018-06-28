<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewbieAward extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newbie_award', function (Blueprint $table) {
            $table->increments('NewbieAwardId');
            
            $table->integer('id')->comment('id');
            $table->integer('item_id')->comment('名字')->nullable();
            $table->integer('count')->comment('数量')->nullable();

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
        Schema::drop('newbie_award');
    }
}
