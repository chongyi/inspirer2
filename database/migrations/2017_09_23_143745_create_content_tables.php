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
            $table->string('name')->index()->nullable()->comment('名称');
            $table->string('title')->index()->comment('标题');
            $table->string('keywords')->index()->default('')->comment('关键字');
            $table->text('description')->nullable()->comment('描述');
            $table->integer('entity_id')->unsigned()->nullable()->comment('实体 ID');
            $table->string('entity_type')->nullable()->comment('实体类型');
            $table->integer('category_id')->unsigned()->nullable()->index()->comment('分类 ID');
            $table->timestamp('published_at')->nullable()->comment('发布时间');
            $table->integer('creator_id')->unsigned()->nullable()->comment('创建人');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['entity_id', 'entity_type'], 'entity_index');
        });

        Schema::create('content_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index()->nullable()->comment('名称');
            $table->string('title')->index()->comment('标题');
            $table->string('keywords')->index()->default('')->comment('关键字');
            $table->text('description')->nullable()->comment('描述');
            $table->string('node_map')->default('')->index()->comment('节点图');
            $table->integer('parent_id')->unsigned()->default(0)->index()->comment('父节点');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('content_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index()->comment('名称');
            $table->timestamps();
        });

        Schema::create('content_entity_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id')->unsigned()->index()->comment('内容 ID');
            $table->string('origin_source')->nullable()->comment('原始来源');
            $table->mediumText('body');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('content_entity_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id')->unsigned()->index()->comment('内容 ID');
            $table->string('body', 400);
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
        Schema::dropIfExists('content_entity_messages');
        Schema::dropIfExists('content_entity_articles');
        Schema::dropIfExists('content_tags');
        Schema::dropIfExists('content_categories');
        Schema::dropIfExists('contents');
    }
}
