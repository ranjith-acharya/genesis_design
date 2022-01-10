<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectExportExcel;
use PDF;
use App\Project;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function exportExcel(Request $request){
        //dd($request->all());
        return Excel::download(new ProjectExportExcel, 'Projects_Monthly.xlsx');
    }

    public function exportPDF(Request $request){
        $startDate =  $request->get('from_date');
        $endDate   = $request->get('to_date');
        $status = $request->get('status');
        if($startDate == "" && $endDate == "" && $status == ""){
            $projects = Project::whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
        }elseif($startDate == "" || $endDate == ""){
            $projects = Project::where('status', $status)->get();
        }elseif($status == ""){
            $projects = Project::whereBetween('created_at', [ $startDate, $endDate ])->get();
        }else{
            $projects = Project::where('status', $status)
                            ->whereBetween('created_at', [ $startDate, $endDate ])->get();
        }
        
        //$projects = Project::all();
        //return $projects;
        $pdf = PDF::loadView('admin.reports.projectMonthly', compact('projects'));
    
        return $pdf->download('Project_Monthly.pdf');
    }
}
