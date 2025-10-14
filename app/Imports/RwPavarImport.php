<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;
use App\Models\RwPavar;
use App\Models\RwPaper;
use App\Models\RwMigration;

class RwPavarImport implements ToModel 
{
    protected $migrationId;
    protected $headers = null; 

    public function __construct($migrationId)
    {
        $this->migrationId = $migrationId;
    }

        public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',', // أو ';' حسب الملف
            'input_encoding' => 'UTF-8',
        ];
    }

    function convertMatri($matri) {
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
    public function model(array $row)
{
    try {
        if ($this->headers === null) {
            if (!in_array('MATRI', $row)) {
                Log::error("لا يمكن العثور على اسم العمود MATRI", ['headers' => $row]);
                return null;
            }
            $this->headers = $row;
            return null;
        }

        $data = array_combine($this->headers, $row);

        if (!$data || !isset($data['MATRI']) || trim($data['MATRI']) === '' || strtoupper($data['MATRI']) === 'MATRI') {
            Log::warning("MATRI غير صالح أو فارغ", ['row' => $row]);
            return null;
        }

        $convertedMatri = $this->convertMatri($data['MATRI']);

        if (!RwPaper::where('MATRI', $convertedMatri)->exists()) {
            Log::error("MATRI غير موجود في rw_papers", ['MATRI' => $convertedMatri, 'row' => $row]);
            return null;
        }

        $migration = RwMigration::find($this->migrationId);
       

        return new RwPavar([
            'MATRI' => $convertedMatri,
            'ID_MIGRATION' => $migration->ID_MIGRATION,
            'IND' => $data['IND'] ?? null,
            'ADM' => $data['ADM'] ?? null,
            'MONTANT' => $data['MONTANT'] ?? null,
        ]);

    } catch (\Exception $e) {
        Log::error("خطأ أثناء استيراد الصف: " . $e->getMessage(), ['row' => $row]);
        return null;
    }
}
}
