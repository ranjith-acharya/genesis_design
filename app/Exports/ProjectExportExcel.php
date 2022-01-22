<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProjectExportExcel implements FromView, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($data){
        $this->data = $data;
    }

    public function view(): View{
        return view('admin.reports.projectExcel', [
            'projects' => $this->data
        ]);
    }

    public function registerEvents(): array
    {
        $datas = $this->data;
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });
        return [
            AfterSheet::class => function(AfterSheet $event) use($datas){
                $a='A5:'.'J'.(5+count($datas));
                $x='A5:J5';
                $event->sheet->getStyle($x)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
                $event->sheet->styleCells(
                    $a,
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ]
                    ]
                        );

                        $event->sheet->getStyle('H6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ADFF2F');

                        $event->sheet->styleCells(
                            'A2:J2',
                            [
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]
                        );
                        $event->sheet->styleCells(
                            'A3:J3',
                            [
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]
                        );

                        $event->sheet->styleCells(
                            $a,
                            [
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]
                        );
                        $event->sheet->styleCells(
                            $a,
                            [
                                'alignment' => [
                                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                ],
                            ]
                        );
                        $event->sheet->getStyle($a)->getAlignment()->setWrapText(true);
                        $event->sheet->getDelegate()->mergeCells('A2:J2');
                        $event->sheet->getRowDimension(2)->setRowHeight(50);
                        $event->sheet->getDelegate()->mergeCells('A3:J3');  
                        $event->sheet->getRowDimension(3)->setRowHeight(30);
                                    
            },
        ];

    }
}
