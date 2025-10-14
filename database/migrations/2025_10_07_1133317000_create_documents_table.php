<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('concours_id'); // المفتاح الأجنبي
            $table->string('type'); // نوع الوثيقة (مثل بطاقة تعريف، شهادة إقامة)
            $table->string('path'); // مسار حفظ الوثيقة
            $table->string('status')->default('قيد الدراسة');
            $table->timestamps();

            $table->foreign('concours_id')->references('id')->on('concours')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
