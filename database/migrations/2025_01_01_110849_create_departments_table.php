<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id(); // الرقم التعريفي
            $table->string('ADM', 50); // رقم الإدارة
            $table->string('name', 255); // اسم الإدارة
            $table->timestamps(); // حقول التوقيت
        });
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
