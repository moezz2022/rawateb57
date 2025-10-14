<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRwMigrationsTable extends Migration
{
    public function up()
    {
        Schema::create('rw_migrations', function (Blueprint $table) {
            $table->id('ID_MIGRATION');
            $table->integer('MONTH');
            $table->integer('YEAR');
            $table->string('LOT')->nullable();
            $table->tinyInteger('STATUS')->default(0);
            $table->text('path');   
            $table->timestamps();
        });
    }   
    public function down()
    {
        Schema::dropIfExists('rw_migrations');
    }
}
