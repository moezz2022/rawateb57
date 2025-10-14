<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryElementsTable extends Migration
{
    public function up()
    {
        Schema::create('salary_elements', function (Blueprint $table) {
            $table->id(); 
            $table->string('IND', length: 3)->unique(); 
            $table->string('nameAR', 255); 
            $table->string('nameFR', 255); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_elements');
    }
}

