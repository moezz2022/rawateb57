<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRwPapersTable extends Migration
{
    public function up()
    {
        Schema::create('rw_papers', function (Blueprint $table) {
            $table->id();
            $table->string('MATRI', 20);
            $table->unsignedBigInteger('ID_MIGRATION');
            $table->string('CATEG', 2)->nullable();
            $table->string('ECH', 2)->nullable();
            $table->string('ADM', 1)->default('');
            $table->decimal('TOTGAIN', 14, 2)->nullable();
            $table->decimal('BRUTSS', 14, 2)->nullable();
            $table->decimal('NBRTRAV', 14, 2)->nullable();
            $table->decimal('RETITS', 14, 2)->nullable();
            $table->decimal('RETSS', 14, 2)->nullable();
            $table->decimal('NETPAI', 14, 2)->nullable();
            $table->timestamps();
            $table->foreign('MATRI')->references('MATRI')->on('employees')->onDelete('cascade');
            $table->foreign('ID_MIGRATION')->references('ID_MIGRATION')->on('rw_migrations')->onDelete('cascade');
            $table->index('MATRI');
        });
    } 
    public function down()
    {
        Schema::dropIfExists('rw_papers');
    }
}
