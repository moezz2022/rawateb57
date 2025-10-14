<?php

namespace App\Exports;

use App\Models\PrimeRendement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class PrimeRendementsExport
{
    public function exportSQL()
    {
        $primeRendements = PrimeRendement::with('employee')->get();

        $sql = "INSERT INTO `prime_rendements` (`MATRI`, `mark`, `absence_days`) VALUES \n";

        $values = [];

        foreach ($primeRendements as $prime) {
            $MATRI = $prime->employee->MATRI ?? 'NULL';
            $mark = $prime->mark;
            $absence_days = $prime->absence_days;

            $values[] = "('$MATRI', $mark, $absence_days)";
        }

        $sql .= implode(",\n", $values) . ";";

        // حفظ الملف في التخزين المؤقت
        $fileName = 'prime_rendements.sql';
        Storage::disk('local')->put($fileName, $sql);

        // تحميل الملف
        return response()->download(storage_path("app/$fileName"))->deleteFileAfterSend(true);
    }
}
