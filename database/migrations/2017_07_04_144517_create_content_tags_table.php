<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_tags', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->unique()->nullable()->comment('名称');
            $table->string('title')->index()->comment('标题');
            $table->text('description')->nullable()->comment('描述');

            $table->timestamps();
        });

        Schema::create('content_tag_related', function (Blueprint $table) {
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('content_id');

            $table->primary(['tag_id', 'content_id'], 'PR_T_C');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_tag_related');
        Schema::dropIfExists('content_tags');
    }
}
