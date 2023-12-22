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
        Schema::create('resources', function (Blueprint $table) {
            $table->comment("资源表");
            $table->id()->comment("主键");
            $table->integer('parent_id')->index()->default(0)->comment("父级ID");
            $table->tinyInteger('type')->comment("资源类型(0:目录,1:文件)");
            $table->string('name')->comment("资源名称");
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
        Schema::dropIfExists('resources');
    }
};
