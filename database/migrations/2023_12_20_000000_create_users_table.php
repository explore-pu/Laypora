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
        Schema::create('users', function (Blueprint $table) {
            $table->comment("用户表");
            $table->id()->comment("主键");
            $table->string('name')->comment("名称");
            $table->string('username')->unique()->comment("用户名");
            $table->string('password')->comment("密码");
            $table->string('api_token')->nullable()->comment('ApiToken');
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('email')->nullable()->unique()->comment("电子邮箱");
            $table->timestamp('email_verified_at')->nullable()->comment("电子邮箱验证时间");
            $table->rememberToken()->comment("RememberToken");
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
        Schema::dropIfExists('users');
    }
};
