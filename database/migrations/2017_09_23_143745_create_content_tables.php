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
            $table->string('keywords')->index()->default('')->comment('关键字');
            $table->text('description')->default('')->comment('描述');
            $table->integer('entity_id')->unsigned()->nullable()->comment('实体 ID');
            $table->string('entity_type')->nullable()->comment('实体类型');
            $table->integer('category_id')->unsigned()->index()->comment('分类 ID');
            $table->timestamp('published_at')->nullable()->comment('发布时间');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['entity_id', 'entity_type'], 'entity_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents');
    }
}
