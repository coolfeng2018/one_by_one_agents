<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelVersion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_version', function (Blueprint $table) {
            $table->increments('ChannelVersionId');
            
            $table->string('id')->comment('渠道+版本<ios_1.0.0>')->nullable();
            $table->integer('curr_version')->comment('当前配置版本号')->nullable();
            $table->string('title')->comment('标题')->nullable();
            $table->string('des')->comment('描述')->nullable();
            $table->string('targetUrl')->comment('URL')->nullable();
            $table->string('img')->comment('图片地址')->nullable();
            $table->string('shareImg')->comment('分享图片和链接')->nullable();
            $table->integer('sharetype')->comment('分享类型')->nullable();
            $table->integer('sharetab')->comment('那种分享')->nullable();
            $table->string('task_share_title')->comment('任务分享标题')->nullable();
            $table->string('task_share_content')->comment('任务分享内容')->nullable();
            $table->string('task_share_url')->comment('任务分享链接')->nullable();
            $table->string('announcement_url')->comment('公告地址')->nullable();
            $table->string('kefu_url')->comment('客服地址')->nullable();
            $table->string('agent_url')->comment('代理商地址')->nullable();
            $table->string('payment_ways')->comment('支付方式')->nullable();

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
        Schema::drop('channel_version');
    }
}
