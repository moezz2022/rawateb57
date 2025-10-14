<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrimeScolariteTable extends Migration
{
    public function up()
    {
        Schema::create('prime_scolarites', function (Blueprint $table) {
            $table->id();
            $table->string('MATRI', 20);
            $table->integer('year');
            $table->integer('ENF')->default(0);
            $table->integer('ENFSCO')->default(0);
            $table->timestamps();
            $table->unique(['MATRI', 'year']);

            
        });
    }

    public function down()
    {
        Schema::dropIfExists('prime_scolarites');
    }
}