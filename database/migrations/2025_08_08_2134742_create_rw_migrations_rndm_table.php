<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRwMigrationsRndmTable extends Migration
{
    public function up()
    {
        Schema::create('rw_migrations_rndm', function (Blueprint $table) {
            $table->id('ID_MIGRATION');
            $table->integer('TRIMESTER');
            $table->integer('YEAR');
            $table->string('TITLE')->nullable();
            $table->string('LOT')->nullable();
            $table->tinyInteger('STATUS')->default(0);
            $table->text('path');   
            $table->timestamps();
        });
    }   
    public function down()
    {
        Schema::dropIfExists('rw_migrations_rndm');
    }
}
    