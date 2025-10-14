<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('prime_rendements', function (Blueprint $table) {
            $table->id();
            $table->string('MATRI', 20);
            $table->string('ADM', 10)->nullable();
            $table->integer('year'); // إضافة السنة
            $table->tinyInteger('quarter'); // 1,2,3,4 بدل period
            $table->integer('mark')->default(40);
            $table->integer('absence_days')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['MATRI', 'year', 'quarter']); // لتجنب تكرار التسجيل لنفس الموظف ونفس الفترة
        });
    }

    public function down()
    {
        Schema::dropIfExists('prime_rendements');
    }
};
