<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('concours', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->foreignId('daira_id')->constrained()->onDelete('cascade');
            $table->foreignId('commune_id')->constrained()->onDelete('cascade');
            $table->string('con_grade');
            $table->string('diploma')->nullable();
            $table->string('specialty')->nullable();
            $table->string('NomArF');
            $table->string('PrenomArF');
            $table->boolean('gender');
            $table->date('DateNaiF');
            $table->string('LieuNaiArF');
            $table->string('birthNum');
            $table->string('familyStatus');
            $table->integer('childrenNumber')->nullable();
            $table->unsignedBigInteger('residenceMunicipality');
            $table->string('personalAddress');
            $table->string('phoneNumber')->unique();
            $table->string('serviceState')->nullable();
            $table->string('serviceNum')->nullable();
            $table->date('servIsDate')->nullable();
            $table->string('status')->default('قيد الدراسة');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concours');
    }
};
