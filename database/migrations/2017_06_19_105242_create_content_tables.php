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
            $table->string('name')->unique()->nullable()->comment('名称');
            $table->string('title')->index()->comment('标题');
            $table->string('keywords')->nullable()->index()->comment('关键字');
            $table->text('description')->nullable()->comment('描述');
            $table->string('entity_type')->comment('内容实体类型');
            $table->unsignedInteger('entity_id')->comment('内容实体标识');
            $table->unsignedInteger('author_id')->index()->nullable()->comment('作者');
            $table->string('author_name')->nullable()->comment('作者名称');

            $table->timestamp('published_at')->nullable()->comment('发布时间');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('content_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cover')->nullable()->comment('封面');
            $table->string('origin_source')->nullable()->comment('原始内容来源，用于转载');
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
