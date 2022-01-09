<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerProjectExport;
use App\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use PDF;

class ReportController extends Controller
{
    public function reportIndex(){
        $user_id = Auth::user()->id;
        $projects = Project::where('customer_id', $user_id)->get();
        return view('customer.reports.index', compact('projects'));
    }

    public function getProjects(){
        $user_id = Auth::user()->id;
        $startDate = request()->input('from_date');
        $endDate   = Carbon::parse(request()->input('to_date'))->addDay();
        if($startDate == "" && $endDate == ""){
            Project::where('customer_id', $user_id)->get();
            //return $project;
        }else{
            return Project::where('customer_id', $user_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        }
    }

    public function exportExcel() 
    {
        return Excel::download(new CustomerProjectExport, 'Projects_All.xlsx');
    }

    public function exportPDF(Request $request){
        $user_id = Auth::user()->id;
        $startDate =  $request->get('from_date');
        $endDate   = Carbon::parse(request()->input('to_date'))->addDay();
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
