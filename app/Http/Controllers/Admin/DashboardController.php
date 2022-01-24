<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProjectExportExcel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Project;
use App\Statics\Statics;
use App\SystemDesign;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Stmt\StaticVar;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customerCount = User::where('role', 'customer')->count();
        $engineerCount = User::where('role', 'engineer')->count();
        $projectsActive = Project::where('status', 'active')->count();
        $projectsInActive = Project::where('status', 'in active')->count();
        $designsAssigned = SystemDesign::where('status_engineer', Statics::DESIGN_STATUS_ENGINEER_ASSIGNED)->count();
        $designsNotAssigned = SystemDesign::where('status_engineer', Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED)->count();
        $projectsHold = Project::where('project_status', Statics::PROJECT_STATUS_ON_HOLD)->count();
        $designsInProcess = SystemDesign::where('status_engineer', Statics::DESIGN_STATUS_ENGINEER_PROGRESS)->count();
        $projectsArchived = Project::where('project_status', Statics::PROJECT_STATUS_ARCHIVED)->count();
        $designsCompleted = SystemDesign::where('status_engineer', Statics::DESIGN_STATUS_ENGINEER_COMPLETED)->count();
        $projectsCancelled = Project::where('project_status', Statics::PROJECT_STATUS_CANCELLED)->count();
        
        $projects = Project::latest()->paginate(5);
        $projectsWeekly = Project::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
        //return Carbon::now()->subWeek()->endOfWeek();
        return view('admin.home', compact('customerCount', 'engineerCount', 'projectsActive', 'projectsInActive', 'projects', 'projectsWeekly', 'designsAssigned', 'designsNotAssigned', 'projectsHold', 'designsInProcess', 'projectsArchived', 'designsCompleted', 'projectsCancelled'));
    }

    // public function projectMonthly(Request $request){
    //     $startDate = $request->get('from_date');
    //     $endDate = $request->get('to_date');
    //     $status = $request->get('status');
    //     if($startDate == "" && $endDate == "" && $status == ""){
    //         return Project::whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
    //     }elseif($startDate == "" || $endDate == ""){
    //         return Project::where('project_status', $status)->get();
    //     }elseif($status == ""){
    //         return Project::whereBetween('created_at', [ $startDate, $endDate ])->get();
    //     }else{
    //         return Project::where('project_status', $status)
    //                         ->whereBetween('created_at', [ $startDate, $endDate ])->get();
    //     }
    // }

    public function projectMonthly(Request $request){
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

        return view('admin.reports.monthlyData', compact('projects'))->render();
    }
    public function projectData(Request $request){
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
        $projects = $this->projectData($request);
        // ob_end_clean(); // this
        // ob_start(); // and this
        //return $projects;
        return Excel::download(new ProjectExportExcel($projects), 'Projects_Monthly.xlsx');
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
