<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop', function (Blueprint $table) {
            $table->increments('ShopId');

            $table->integer('id')->comment('任务id');
            $table->string('name')->comment('商品名称')->nullable();
            $table->string('price')->comment('价格');//dict[2]
                // $table->integer('currency')->comment('货币类型');
                // $table->integer('amount')->comment('货币数量');
            $table->string('goods')->comment('商品');//dict[2]
                // $table->integer('item_id')->comment('道具id');
                // $table->integer('item_count')->comment('道具数量');
            $table->integer('index')->comment('显示序号')->nullable();
            $table->integer('discount')->comment('特惠标识')->nullable();
            $table->string('icon_name')->comment('贴图名称')->nullable();
            $table->string('ios_goods_id')->comment('苹果id')->nullable();
            
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
        Schema::drop('shop');
    }
}
