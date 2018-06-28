<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('value', function (Blueprint $table) {
            $table->increments('ValueId');

            $table->string('id')->comment('id=>BASE_COMPENSATION_COND(破产补助条件)BASE_COMPENSATION_COINS(破产补助每次给予金币值)BASE_COMPENSATION_TIMES_LIMIT(破产补助每天次数限制)HONGHEI_FEE_PERCENT(红黑大战抽水百分比)');
            $table->double('value', 50, 2)->comment('value')->nullable();//json格式,因为生成格式是int，最后确定使用浮点型,保留2位小数

            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }
    ////0-9999是前端模块定义文字，10000-19999后端发送给前端显示的错误信息，20000-29999是后端发送给前端不方便显示需要特殊处理的错误码
    //1-100账号；101-200登录；201-300通用提示框;401-500设置；501-600补助金

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('value');
    }
}
