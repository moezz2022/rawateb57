<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBarcodeToEmployeesAndCreateAttendancesTable extends Migration
{
    public function up()
    {
        // إضافة حقل barcode إلى employees (لو لم يكن موجودًا)
        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                if (! Schema::hasColumn('employees', 'barcode')) {
                    $table->string('barcode')->nullable()->after('MATRI')->unique(false);
                }
            });
        }

        // إنشاء جدول attendances
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date')->index();
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('status')->nullable();
            $table->text('note')->nullable();
            $table->string('device')->nullable(); // اسم الجهاز أو IP إن رغبت
            $table->timestamps();

            $table->unique(['employee_id','date']); // سطر واحد لكل موظف لكل يوم (يمكن تغييره)
        });
    }

    public function down()
    {
        if (Schema::hasColumn('employees', 'barcode')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('barcode');
            });
        }

        Schema::dropIfExists('attendances');
    }
}
