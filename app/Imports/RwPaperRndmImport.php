<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;
use App\Models\PaperRndm;
use App\Models\RwMigrationRndm;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;

class RwPaperRndmImport implements ToModel
{
    protected $migrationId;
    protected $headers; // لتخزين أسماء الأعمدة

    public function __construct($migrationId)
    {
        $this->migrationId = $migrationId;
        $this->headers = null; // ستحدد لاحقًا عند قراءة الصف الأول
    }
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',', // أو ';' حسب الملف
            'input_encoding' => 'UTF-8',
        ];
    }

    function convertMatri($matri)
    {
        // إذا كان يحتوي على "000" + حرف (A-Z) + أرقام → تحويل الحرف إلى رقم
        if (preg_match('/^000([A-Z])(\d{1,})$/i', $matri, $matches)) {
            $prefix = strtoupper($matches[1]);
            $numericPrefix = ord($prefix) - ord('A') + 10;
            return $numericPrefix . $matches[2];
        }

        // إذا كان يبدأ بـ "000" أو "00" متبوعًا بأرقام فقط → حذف الأصفار الزائدة
        if (preg_match('/^0{2,}(\d+)$/', $matri, $matches)) {
            return ltrim($matches[1], '0'); // حذف جميع الأصفار من اليسار
        }

        return $matri;
    }


    public function model(array $row)
    {
        try {
            if ($this->headers === null) {
                $this->headers = $row;
                return null;
            }

            if (count($this->headers) !== count($row)) {
                Log::warning(" عدد الأعمدة في الصف لا يتطابق مع التوقعات", ['row' => $row]);
                return null;
            }

            $data = array_combine($this->headers, $row);

            if (!$data) {
                Log::warning(" فشل في مطابقة الأعمدة", ['row' => $row]);
                return null;
            }

            $requiredColumns = [
                'MATRI',
                'CATEG',
                'ECH',
                'ADM',
                'SALBASE',
                'TOTGAIN',
                'BRUTSS',
                'RETITS',
                'RETSS',
                'NETPAI',
                'BRUTMENS',
                'TAUX',
                'JRPRIME'
            ];

            foreach ($requiredColumns as $column) {
                if (!array_key_exists($column, $data)) {
                    Log::warning(" العمود مفقود: $column", ['row' => $row]);
                    return null;
                }
            }

            $migration = RwMigrationRndm::findOrFail($this->migrationId);

            $paper = new PaperRndm ([
                'MATRI' => $this->convertMatri($data['MATRI']),
                'ID_MIGRATION' => $migration->ID_MIGRATION,
                'CATEG' => substr(strtolower($data['CATEG']), 0, 10),
                'ECH' => substr($data['ECH'], 0, 10),
                'ADM' => substr($data['ADM'], 0, 10),
                'SALBASE' => is_numeric($data['SALBASE']) ? $data['SALBASE'] : 0,
                'TOTGAIN' => is_numeric($data['TOTGAIN']) ? $data['TOTGAIN'] : 0,
                'BRUTSS' => is_numeric($data['BRUTSS']) ? $data['BRUTSS'] : 0,
                'RETITS' => is_numeric($data['RETITS']) ? $data['RETITS'] : 0,
                'RETSS' => is_numeric($data['RETSS']) ? $data['RETSS'] : 0,
                'NETPAI' => is_numeric($data['NETPAI']) ? $data['NETPAI'] : 0,
                'BRUTMENS' => is_numeric($data['BRUTMENS']) ? $data['BRUTMENS'] : 0,
                'TAUX' => is_numeric($data['TAUX']) ? $data['TAUX'] : 0,
                'JRPRIME' => is_numeric($data['JRPRIME']) ? $data['JRPRIME'] : 0,
            ]);

            $paper->save();
            return $paper;
        } catch (\Exception $e) {
            Log::error(' خطأ أثناء استيراد الصف: ' . $e->getMessage(), ['row' => $row]);
            return null;
        }
    }
}
