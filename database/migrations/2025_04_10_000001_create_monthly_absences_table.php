<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyAbsencesTable extends Migration
{
    public function up()
    {
        Schema::create('monthly_absences', function (Blueprint $table) {
            $table->id();
            $table->string('MATRI', 20);
            $table->integer('month');
            $table->integer('year');
            $table->integer('absence_days')->default(0);
            $table->string('absence_reason')->nullable();
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('monthly_absences');
    }
}