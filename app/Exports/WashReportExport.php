<?php

namespace App\Exports;

use App\Models\Wash;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class WashReportExport implements FromCollection, WithHeadings, WithEvents, WithTitle
{
    protected $report;

    public function __construct(Wash $report)
    {
        $this->report = $report;
    }

    public function collection()
    {
        $rows = [];
        $serial = 1;
        $totalOrderQty = 0;
        $totalSend = 0;
        $totalReceive = 0;
        $totalBalance = 0;

        foreach ($this->report->wash as $row) {
            $orderQty = (int)($row['order_qty'] ?? 0);
            $send = (int)($row['send'] ?? 0);
            $receive = (int)($row['received'] ?? 0);
            $balance = $send - $receive;

            $rows[] = [
                'Serial No'         => $serial++,
                'Style No'          => $this->report->order->style_no ?? 'N/A',
                'Garment Type'      => $this->report->garment_type ?? 'N/A',
                'Color'             => $row['color'] ?? '',
                'Order Quantity'    => $orderQty,
                'Send'              => $send,
                'Receive'           => $receive,
                'Balance'           => $balance,
                'Remarks'           => $row['remarks'] ?? '',
            ];

            $totalOrderQty += $orderQty;
            $totalSend += $send;
            $totalReceive += $receive;
            $totalBalance += $balance;
        }

        // Add total row at the end
        $rows[] = [
            'Serial No'      => '',
            'Style No' => '',
            'Garment Type'   => '',
            'Color'          => 'Total',
            'Order Quantity' => $totalOrderQty,
            'Send'           => $totalSend,
            'Receive'        => $totalReceive,
            'Balance'        => $totalBalance,
            'Remarks'        => '',
        ];

        return collect($rows);
    }

    public function headings(): array
    {
        $date = Carbon::parse($this->report->date)->format('d-m-Y');
        return [
            ['A.Z Group'],
            ['295/Ja/4/A Rayer Bazar, Dhaka-1209'],
            ['Daily Wash Report'],
            [],
            ['', '', '', '', '', '', '', '', 'Date: ' . $date],
            ['Serial No', 'Style No', 'Garment Type', 'Color', 'Order Quantity', 'Send', 'Receive', 'Balance', 'Remarks'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge and style headers
                foreach (['A1:I1', 'A2:I2', 'A3:I3'] as $range) {
                    $event->sheet->mergeCells($range);
                }
                foreach (['A1', 'A2', 'A3'] as $cell) {
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
                $event->sheet->getStyle('I5')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);
                $event->sheet->getStyle('A6:I6')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                // Borders for table
                $rowCount = count($this->report->wash);
                $totalRow = 6 + $rowCount + 1; // 6 heading rows, then data, then total row
                $lastRow = $totalRow;
                $cellRange = "A6:I{$lastRow}";
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
                // Bold the total row
                $event->sheet->getStyle("A{$totalRow}:H{$totalRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);
            }
        ];
    }

    public function title(): string
    {
        return 'Wash Report';
    }
}
