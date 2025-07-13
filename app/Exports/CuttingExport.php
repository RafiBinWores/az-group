<?php

namespace App\Exports;

use App\Models\Cutting;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class CuttingExport implements FromCollection, WithHeadings, WithEvents, WithTitle
{
    protected $cutting;

    public function __construct($cutting)
    {
        $this->cutting = $cutting;
    }

    public function collection()
    {
        $rows = [];
        $serial = 1;
        $totalQty = 0;
        foreach ($this->cutting->cutting as $row) {
            $rows[] = [
                'Serial No' => $serial++,
                'Order Style No' => $this->cutting->order->style_no ?? 'N/A',
                'Color' => $row['color'],
                'Quantity' => $row['qty'],
                'Remarks' => $row['remarks'] ?? '',
            ];
            $totalQty += (int) $row['qty'];
        }

        // Add Total row at the end
        $rows[] = [
            'Serial No' => '',
            'Order Style No' => '',
            'Color' => 'Total',
            'Quantity' => $totalQty,
            'Remarks' => '',
        ];

        return collect($rows);
    }

    public function headings(): array
    {
        $date = Carbon::parse($this->cutting->created_at)->format('d-m-Y');
        return [
            ['A.Z Group'],
            ['295/Ja/4/A Rayer Bazar, Dhaka-1209'],
            ['Daily Cutting Report'],
            [],
            ['', '', '', '', 'Date: ' . $date],
            ['Serial No', 'Order Style No', 'Color', 'Quantity', 'Remarks'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Merge and style headers
                foreach(['A1:E1','A2:E2','A3:E3'] as $range) {
                    $event->sheet->mergeCells($range);
                }
                foreach(['A1','A2','A3'] as $cell) {
                    $event->sheet->getStyle($cell)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => ($cell === 'A1' ? 16 : ($cell === 'A3' ? 14 : 12)),
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                }
                $event->sheet->getStyle('E4')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);
                $event->sheet->getStyle('A6:E6')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                // Borders for table
                $rowCount = count($this->cutting->cutting);
                $totalRow = 6 + $rowCount + 1; // 6 heading rows, then data, then total row
                $lastRow = $totalRow;
                $cellRange = "A6:E{$lastRow}";
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
                // Bold the total row
                $event->sheet->getStyle("A{$totalRow}:E{$totalRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);
            }
        ];
    }

    public function title(): string
    {
        return 'Cutting Report';
    }
}
