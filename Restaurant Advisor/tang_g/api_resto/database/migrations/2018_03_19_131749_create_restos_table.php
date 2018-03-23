<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('user_id')->nullable();
            $table->string('categorie');
            $table->string('description');
            $table->integer('note');
            $table->string('address');
            $table->string('phone');
            $table->string('website');
            $table->string('open_week');
            $table->string('close_week');
            $table->string('open_weekend');
            $table->string('close_weekend');
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
        Schema::dropIfExists('restos');
    }
}
