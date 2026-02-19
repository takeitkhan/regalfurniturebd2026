<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRegisterLogsTable extends Migration
{
    public function up()
    {
        Schema::create('user_register_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('ip', 64)->nullable()->index();
            $table->string('user_agent', 512)->nullable();
            $table->string('source', 64)->nullable()->index();
            $table->string('status', 32)->nullable()->index();
            $table->string('reason', 255)->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_register_logs');
    }
}
