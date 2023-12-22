<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markdowns', function (Blueprint $table) {
            $table->comment("内容表");
            $table->id()->comment("主键");
            $table->integer('resource_id')->index()->comment("资源ID");
            $table->text('content')->comment("资源内容");
            $table->softDeletes()->comment('软删除');
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
        Schema::dropIfExists('markdowns');
    }
};
