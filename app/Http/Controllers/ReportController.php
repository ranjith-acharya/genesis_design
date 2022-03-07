<?php

namespace App\Http\Controllers;

use App\Exports\CustomerProjectExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use PDF;

class ReportController extends Controller
{
    public function reportIndex(){
        $user_id = Auth::user()->id;
        $startDate = request()->input('from_date');
        $endDate   = request()->input('to_date');
        //return $endDate;
        //$projects = Project::where('customer_id', $user_id)->get();
        if($startDate == "" && $endDate == ""){
            $projects = Project::where('customer_id', $user_id)->get();
        }else{
            $projects =  Project::where('customer_id', $user_id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
        }
        return view('customer.reports.index', compact('projects'));
    }

    public function getProjects(Request $request){
        $user_id = Auth::user()->id;
        $startDate =  $request->get('from_date');
        $endDate   = $request->get('to_date');
        // $status = $request->get('status');
        if($startDate == "" && $endDate == ""){
            $projects = Project::where('customer_id', $user_id)->get();
        }else{
            $projects = Project::where('customer_id', $user_id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
        }

        return $projects;
    }

    public function exportExcel(Request $request){
        //dd($request->all());
        $projects = $this->getProjects($request);
        // ob_end_clean(); // this
        // ob_start(); // and this
        return Excel::download(new CustomerProjectExport($projects), 'Projects_All.xlsx');
    }

    public function exportPDF(Request $request){
        $user_id = Auth::user()->id;
        $startDate =  $request->get('from_date');
        $endDate   = $request->get('to_date');
        if($startDate == "" && $endDate == ""){
            $projects = Project::where('customer_id', $user_id)->get();
            //return $project;
        }else{
            $projects = Project::where('customer_id', $user_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        }
        
        //$projects = Project::all();
        //return $projects;
        $pdf = PDF::loadView('customer.reports.projectAll', compact('projects'));
    
        return $pdf->download('Project_All.pdf');
    }
}
