<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSetInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_set_infos', function (Blueprint $table) {
            $table->id();
            $table->integer("product_set_id")->unsigned();
            $table->string("title");
            $table->string("sub_title")->nullable();
            $table->text("description")->nullable();
            $table->tinyInteger("active")->default(true);
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
        Schema::dropIfExists('product_set_infos');
    }
}
