<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInteriorImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interior_images', function (Blueprint $table) {
            $table->id();
            $table->integer('image_id');
            $table->integer('interior_id');
            $table->string('title')->default(null);
            $table->string('caption')->default(null);
            $table->string('info')->default(null);
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
        Schema::dropIfExists('interior_images');
    }
}
