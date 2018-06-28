<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share', function (Blueprint $table) {
            $table->increments('ShareId');

            $table->string('channel')->comment('渠道')->nullable();
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
        Schema::drop('share');
    }
}
