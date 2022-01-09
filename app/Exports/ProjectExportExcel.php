<?php

namespace App\Exports;

use App\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnCellIterator;

class ProjectExportExcel implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        return Project::all();
    }
    public function headings(): array{
        return [
            'id',
            'Name',
            'Description',
            'Street_1',
            'Street_2',
            'City',
            'State',
            'Zip',
            'Country',
            'Latitude',
            'Longitude',
            'Status',
            'Customer_ID',
            'Engineer_ID',
            'Company_ID',
            'Project_ID',
            'Created_at',
            'Updated_at',
        ];
    }
    public function styles(Worksheet $sheet){
        return [
            1 => ['font' => ['bold' => true],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
