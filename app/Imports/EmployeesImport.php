<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Group;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeesImport implements ToModel, WithHeadingRow
{
    private $headers = null;

    public function model(array $row)
    {
        try {
            // تحويل جميع المفاتيح إلى أحرف كبيرة لضمان المطابقة الصحيحة
            $row = array_change_key_case($row, CASE_UPPER);

            // التحقق من وجود جميع الأعمدة المطلوبة
            $requiredColumns = [
                'MATRI',
                'NOM',
                'PRENOM',
                'NOMA',
                'PRENOMA',
                'DATNAIS',
                'CODFONC',
                'ADM',
                'AFFECT',
                'NUMSS',
                'SITFAM',
                'ENF10',
                'ECH',
                'CLECPT',
                'DATENT',
            ];

            foreach ($requiredColumns as $column) {
                if (!array_key_exists($column, $row)) {
                    Log::warning("⚠️ العمود مفقود: $column", ['row' => $row]);
                    return null;
                }
            }

            // التحقق من أن MATRI ليس فارغًا
            if (empty($row['MATRI'])) {
                Log::error('❌ خطأ: قيمة MATRI مفقودة', ['row' => $row]);
                return null;
            }

            return new Employee([
                'MATRI' => trim($row['MATRI']),
                'NOM' => trim($row['NOM'] ?? ''),
                'PRENOM' => trim($row['PRENOM'] ?? ''),
                'NOMA' => trim($row['NOMA'] ?? ''),
                'PRENOMA' => trim($row['PRENOMA'] ?? ''),
                'SITFAM' => in_array($row['SITFAM'], ['C00', 'C01', 'C02', 'C03', 'C04', 'M00', 'M01', 'M02', 'M03', 'M04', 'M05', 'M06'])
                    ? $row['SITFAM']
                    : 'C00',
                'ENF10' => $row['ENF10'] ?? 0,
                'CLECPT' => $row['CLECPT'] ?? null,
                'NUMSS' => $row['NUMSS'] ?? null,
                'CODFONC' => $row['CODFONC'] ?? null,
                'ADM' => $row['ADM'] ?? null,
                'DATNAIS' => is_numeric($row['DATNAIS'])
                    ? Date::excelToDateTimeObject(floatval($row['DATNAIS']))->format('Y-m-d')
                    : '1970-01-01',
                'DATENT' => is_numeric($row['DATENT'])
                    ? Date::excelToDateTimeObject(floatval($row['DATENT']))->format('Y-m-d')
                    : '1970-01-01',
                'ECH' => $row['ECH'] ?? null,
                'AFFECT' => DB::table('groups')->where('AFFECT', $row['AFFECT'])->exists()
                    ? $row['AFFECT']
                    : '570000',
            ]);
        } catch (\Exception $e) {
            Log::error('❌ خطأ أثناء استيراد البيانات: ' . $e->getMessage(), [
                'row' => $row,
                'missing_keys' => array_diff($requiredColumns, array_keys($row))
            ]);
            return null;
        }
    }

}
