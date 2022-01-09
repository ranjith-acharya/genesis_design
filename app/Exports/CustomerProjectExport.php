<?php

namespace App\Exports;

use App\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Carbon;

class CustomerProjectExport implements FromCollection, WithStyles, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user_id =  Auth::user()->id;
        $startDate = request()->input('from_date') ;
        $endDate   = Carbon::parse(request()->input('to_date'))->addDay();
        if($startDate == "" && $endDate == ""){
            return Project::where('customer_id', $user_id)->get();
            //return $project;
        }else{
            return Project::where('customer_id', $user_id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
        }
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
            'Status',
            'Created_at',
        ];
    }
    
    public function map($row): array{
        return [
            $row->id,
            $row->name,
            $row->description,
            $row->street_1,
            $row->street_2,
            $row->city,
            $row->state,
            $row->zip,
            $row->country,
            $row->status,
            $row->created_at,
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
