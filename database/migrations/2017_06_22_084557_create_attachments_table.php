<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');

            $table->string('path')->comment('资源路径');
            $table->string('disk')->comment('资源储存位置');
            $table->string('token')->unique()->comment('资源令牌');
            $table->string('mime')->comment('资源 MIME');
            $table->string('origin_name')->comment('资源原始名称');
            $table->integer('size')->comment('资源大小');

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
        Schema::dropIfExists('attachments');
    }
}
