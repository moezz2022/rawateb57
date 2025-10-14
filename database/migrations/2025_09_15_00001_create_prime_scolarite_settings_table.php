<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrimeScolariteSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('prime_scolarite_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_open')->default(true);
            $table->integer('year')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prime_scolarite_settings');
    }
}