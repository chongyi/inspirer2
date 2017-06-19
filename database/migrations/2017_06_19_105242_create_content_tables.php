<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->index()->comment('标题');
            $table->string('keywords')->index()->comment('关键字');
            $table->text('description')->comment('描述');
            $table->string('entity_type')->comment('内容实体类型');
            $table->unsignedInteger('entity_id')->comment('内容实体标识');

            $table->timestamp('published_at')->nullable()->comment('发布时间');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('content_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('content');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_articles');
        Schema::dropIfExists('contents');
    }
}
