<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRwPavarTable extends Migration
{
    public function up()
    {
        Schema::create('rw_pavar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('MATRI', 20);
            $table->unsignedBigInteger('ID_MIGRATION');
            $table->string('IND', 3);
            $table->string('ADM', 1)->default('');
            $table->decimal('MONTANT', 14, 2)->nullable();
            $table->timestamps();    
            $table->index(['MATRI', 'IND']);
            $table->foreign('ID_MIGRATION')->references('ID_MIGRATION')->on('rw_migrations')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('rw_pavar');
    }
}
