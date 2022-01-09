<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectExportExcel;
use PDF;
use App\Project;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request){
        //dd($request->all());
        return Excel::download(new ProjectExportExcel, 'Projects_Monthly.xlsx');
    }

    public function exportPDF(Request $request){
        $startDate =  $request->get('from_date');
        $endDate   = Carbon::parse(request()->input('to_date'))->addDay();
        if($startDate == "" && $endDate == ""){
            $projects = Project::all();
        }else{
            $projects = Project::whereBetween('created_at', [ $startDate, $endDate ] )->get();
        }
        
        //$projects = Project::all();
        //return $projects;
        $pdf = PDF::loadView('admin.reports.projectMonthly', compact('projects'));
    
        return $pdf->download('Project_Monthly.pdf');
    }
    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
