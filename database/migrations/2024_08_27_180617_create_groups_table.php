<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('AFFECT', 8)->collation('utf8mb4_unicode_ci');
            $table->string('name');
            $table->string('type'); 
            $table->foreignId('parent_id')->nullable()->constrained('groups')->onDelete('cascade');
            $table->timestamps();
        
            // 🔹 إضافة فهرس لمفتاح AFFECT ليسمح باستخدامه كمفتاح أجنبي
            $table->index('AFFECT');
        });
        
    }
     

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
