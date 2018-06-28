<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBehaviorRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_behavior_record', function (Blueprint $table) {
            $table->increments('UserBehaviorRecordId');

            $table->string('table_name')->comment('表名');
            $table->string('server_record')->comment('服务端记录');//json格式保留
            $table->string('customer_record')->comment('客户端记录');//json格式保留
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
        Schema::drop('user_behavior_record');
    }
}
