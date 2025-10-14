<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdmToEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('ADM')->nullable()->after('CODFONC'); 
            // replace 'some_column' باسم العمود الذي تريد إضافته بعده، أو اتركه بدون 'after' ليضاف في النهاية
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('ADM');
        });
    }
}
