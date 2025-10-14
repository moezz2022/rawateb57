<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
 
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('NOM', 20);
            $table->string('PRENOM', 20);
            $table->string('NOMA', 20);
            $table->string('PRENOMA', 20);
            $table->date('DATNAIS');
            $table->string('SITFAM', 3)->nullable();
            $table->string('ENF10', 3)->nullable();
            $table->string('MATRI', 20)->unique(); // جعل MATRI فريدًا
            $table->string('CLECPT', 2)->nullable();
            $table->string('NUMSS', 20)->nullable();
            $table->string('CODFONC', 10)->nullable();
            $table->date('DATENT');
            $table->string('ECH', 2)->nullable();
            $table->string('AFFECT', 20)->nullable(); // إضافة AFFECT في جدول employees
            $table->timestamps();
    
            // إضافة المفتاح الأجنبي من AFFECT في employees إلى AFFECT في groups
            $table->foreign('AFFECT')->references('AFFECT')->on('groups')->onDelete('set null');
        });
    }
    
  
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}