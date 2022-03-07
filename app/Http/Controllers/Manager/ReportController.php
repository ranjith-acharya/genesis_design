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
    public function filterProject(Request $request){
        $startDate =  $request->get('from_date');
        $endDate   = $request->get('to_date');
        $status = $request->get('status');
        if($startDate != "" && $endDate != "" && $status != ""){
            $projects = Project::where('project_status', $status)
                                    ->whereBetween('created_at', [ $startDate, $endDate ])->get();
        }elseif($startDate != "" && $endDate != "" && $status == ""){
            $projects = Project::whereBetween('created_at', [ $startDate, $endDate ])->get();
        }elseif($status != "" && $startDate == "" && $endDate == ""){
            $projects = Project::where('project_status', $status)->get();
        }else{
            $projects = Project::all();
        }

        return $projects;
    }

    public function exportExcel(Request $request){
        //dd($request->all());
        $projects = $this->filterProject($request);
        // ob_end_clean(); // this
        // ob_start(); // and this
        return Excel::download(new ProjectExportExcel($projects), 'Projects_Monthly.xlsx');
    }

    public function exportPDF(Request $request){
        $startDate =  $request->get('from_date');
        $endDate   = $request->get('to_date');
        $status = $request->get('status');
        if($startDate != "" && $endDate != "" && $status != ""){
            $projects = Project::where('project_status', $status)
                                    ->whereBetween('created_at', [ $startDate, $endDate ])->get();
        }elseif($startDate != "" && $endDate != "" && $status == ""){
            $projects = Project::whereBetween('created_at', [ $startDate, $endDate ])->get();
        }elseif($status != "" && $startDate == "" && $endDate == ""){
            $projects = Project::where('project_status', $status)->get();
        }else{
            $projects = Project::all();
        }
        
        //$projects = Project::all();
        //return $projects;
        $pdf = PDF::loadView('admin.reports.projectMonthly', compact('projects'));
    
        return $pdf->download('Project_Monthly.pdf');
    }
}
