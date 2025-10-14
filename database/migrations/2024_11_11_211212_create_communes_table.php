<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunesTable extends Migration
{

    public function up()
    {
        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'code_commune')->unique();
            $table->string('name'); 
            $table->foreignId('daira_id')->constrained()->onDelete('cascade'); // مفتاح خارجي للدائرة
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('communes');
    }
}
