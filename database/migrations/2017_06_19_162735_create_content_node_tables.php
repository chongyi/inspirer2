<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentNodeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_node_channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('频道名称');
            $table->string('display_name')->index()->comment('频道（可读）名称');
            $table->text('description')->comment('描述');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('content_tree_nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('channel_id')->index()->comment('频道 ID');
            $table->string('title')->index()->comment('标题');
            $table->string('keywords')->index()->comment('关键字');
            $table->text('description')->comment('描述');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('content_tree_node_related', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_id')->comment('节点 ID');
            $table->unsignedInteger('content_id')->comment('内容 ID');

            $table->timestamps();

            $table->unique(['node_id', 'content_id'], 'N_C_UNIQUE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_tree_node_related');
        Schema::dropIfExists('content_tree_nodes');
        Schema::dropIfExists('content_node_channels');
    }
}
