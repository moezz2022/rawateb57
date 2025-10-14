<?php

namespace App\Exports;

use App\Models\PrimeScolarite;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PrimeScolariteExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {
        return PrimeScolarite::with('employee')
            ->where('year', $this->year)
            ->get();
    }

    public function headings(): array
    {
        return [
            'رمز الموظف',
            'اللقب والاسم',
            'الرتبة',
            'المؤسسة',
            'عدد الأولاد',
            'عدد الأولاد المتمدرسين',
        ];
    }

    public function map($scolarite): array
    {
        return [
            $scolarite->employee->MATRI,
            $scolarite->employee->NOMA . ' ' . $scolarite->employee->PRENOMA,
            $scolarite->employee->grade->name ?? 'غير متوفر',
            !empty($scolarite->employee->PRIMAIRE)
            ? ($scolarite->employee->primaireGroup->name ?? 'غير متوفر')
            : ($scolarite->employee->group->name ?? 'غير معروف'),

            $scolarite->ENF,
            $scolarite->ENFSCO,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 13]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '6F42C1']
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF']
                    ]
                ]);

                // تطبيق حدود على جميع الخلايا
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle('A1:F' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // تطبيق اتجاه النص من اليمين إلى اليسار
                $event->sheet->getDelegate()->setRightToLeft(true);
            }
        ];
    }
}