<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;
use App\Models\RwPaper;
use App\Models\RwMigration;
use App\Models\Employee;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;

class RwPaperImport implements ToModel
{
    protected $migrationId;
    protected $headers;

    public function __construct($migrationId)
    {
        $this->migrationId = $migrationId;
        $this->headers = null;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',', // أو ';' حسب الملف
            'input_encoding' => 'UTF-8',
        ];
    }

    protected function convertMatri($matri)
    {
        if (preg_match('/^000([A-Z])(\d{1,})$/i', $matri, $matches)) {
            $prefix = strtoupper($matches[1]);
            $numericPrefix = ord($prefix) - ord('A') + 10;
            return $numericPrefix . $matches[2];
        }

        if (preg_match('/^0{2,}(\d+)$/', $matri, $matches)) {
            return ltrim($matches[1], '0');
        }

        return $matri;
    }

    /**
     * 🔤 إصلاح الترميز UTF-8 لأي نص
     */
    protected function fixEncoding($value)
    {
        if (is_string($value)) {
            return mb_convert_encoding($value, 'UTF-8', 'auto');
        }
        return $value;
    }

    public function model(array $row)
    {
        try {
            // أول صف = headers
            if ($this->headers === null) {
                $this->headers = $row;
                Log::info("✅ تم اكتشاف الأعمدة", ['headers' => $this->headers]);
                return null;
            }

            // تحقق من عدد الأعمدة
            if (count($this->headers) !== count($row)) {
                Log::warning("⚠️ عدد الأعمدة لا يطابق التوقعات", ['row' => $row]);
                return null;
            }

            // دمج الأعمدة مع القيم
            $data = array_combine($this->headers, $row);

            if (!$data) {
                Log::warning("⚠️ فشل في مطابقة الأعمدة", ['row' => $row]);
                return null;
            }

            $migration = RwMigration::findOrFail($this->migrationId);

            // ✅ معالجة MATRI
            $matri = $this->convertMatri($data['MATRI'] ?? null);

            if (!$matri) {
                Log::warning("⚠️ MATRI غير صالح", ['row' => $data]);
                return null;
            }

            // ✅ إعداد بيانات الموظف (مع إصلاح الترميز)
            $employeeData = [
                'NOM'     => $this->fixEncoding(trim($data['NOM'] ?? '')),
                'PRENOM'  => $this->fixEncoding(trim($data['PRENOM'] ?? '')),
                'NOMA'    => $this->fixEncoding(trim($data['NOMA'] ?? '')),
                'PRENOMA' => $this->fixEncoding(trim($data['PRENOMA'] ?? '')),

                'DATNAIS' => (!empty($data['DATNAIS']) && is_numeric($data['DATNAIS']))
                    ? Date::excelToDateTimeObject(floatval($data['DATNAIS']))->format('Y-m-d')
                    : null,

                'SITFAM'  => in_array($data['SITFAM'] ?? 'C00', [
                        'C00','C01','C02','C03','C04',
                        'M00','M01','M02','M03','M04','M05','M06'
                    ])
                    ? $data['SITFAM']
                    : 'C00',

                'ENF10'   => $data['ENF10'] ?? 0,
                'CLECPT'  => $data['CLECPT'] ?? null,
                'NUMSS'   => $data['NUMSS'] ?? null,

                'DATENT'  => (!empty($data['DATENT']) && is_numeric($data['DATENT']))
                    ? Date::excelToDateTimeObject(floatval($data['DATENT']))->format('Y-m-d')
                    : null,

                'CODFONC' => $data['CODFONC'] ?? null,
                'ECH'     => $data['ECH'] ?? null,
                'AFFECT'  => DB::table('groups')->where('AFFECT', $data['AFFECT'] ?? '')->exists()
                    ? $data['AFFECT']
                    : '570000',
                'ADM'     => $data['ADM'] ?? null,
            ];

            // ✅ إدراج فقط (إذا غير موجود)
            if (!Employee::where('MATRI', $matri)->exists()) {
                Log::info("👤 محاولة إدراج موظف (بدون تحديث)", [
                    'MATRI' => $matri,
                    'employee' => $employeeData
                ]);
                Employee::create(array_merge(['MATRI' => $matri], $employeeData));
            }

            // ✅ إنشاء ورقة rw_papers
            $paper = new RwPaper([
                'MATRI'     => $matri,
                'ID_MIGRATION' => $migration->ID_MIGRATION,
                'CATEG'     => substr(strtolower($data['CATEG'] ?? ''), 0, 10),
                'ECH'       => substr($data['ECH'] ?? '', 0, 10),
                'ADM'       => substr($data['ADM'] ?? '', 0, 10),
                'TOTGAIN'   => is_numeric($data['TOTGAIN'] ?? null) ? $data['TOTGAIN'] : 0,
                'BRUTSS'    => is_numeric($data['BRUTSS'] ?? null) ? $data['BRUTSS'] : 0,
                'NBRTRAV'   => is_numeric($data['NBRTRAV'] ?? null) ? $data['NBRTRAV'] : 0,
                'RETITS'    => is_numeric($data['RETITS'] ?? null) ? $data['RETITS'] : 0,
                'RETSS'     => is_numeric($data['RETSS'] ?? null) ? $data['RETSS'] : 0,
                'NETPAI'    => is_numeric($data['NETPAI'] ?? null) ? $data['NETPAI'] : 0,
            ]);

            $paper->save();
            return $paper;

        } catch (\Exception $e) {
            Log::error('❌ خطأ أثناء استيراد الصف: ' . $e->getMessage(), ['row' => $row]);
            return null;
        }
    }
}
