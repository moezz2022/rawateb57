<?php

namespace App\Exports;

use App\Models\MonthlyAbsence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MonthlyAbsencesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return MonthlyAbsence::with('employee')
            ->where('month', $this->month)
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
            'عدد أيام الغياب',
            'سبب الغياب',
        ];
    }

    public function map($absence): array
    {
        return [
            $absence->employee->MATRI,
            $absence->employee->NOMA . ' ' . $absence->employee->PRENOMA,
            $absence->employee->grade->name ?? 'غير متوفر',
            $absence->employee->group->name ?? 'غير معروف',
            $absence->absence_days,
            $absence->absence_reason,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
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
                $event->sheet->getStyle('A1:F'.$lastRow)->applyFromArray([
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