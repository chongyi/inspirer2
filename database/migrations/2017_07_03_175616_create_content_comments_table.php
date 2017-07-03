<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_comments', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('parent_id')->default(0)->comment('父评论 ID');
            $table->unsignedInteger('content_id')->index()->comment('内容 ID');
            $table->unsignedInteger('entity_id')->nullable()->comment('内容实体 ID');
            $table->string('entity_type')->nullable()->comment('内容实体类型');
            $table->unsignedInteger('user_id')->nullable()->comment('用户 ID');
            $table->text('content')->comment('评论内容');
            $table->text('discussant_context')->nullable()->comment('评论人上下文信息（对于非本站用户）');
            $table->string('discussant')->nullable()->comment('评论人');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_comments');
    }
}
