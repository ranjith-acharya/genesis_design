<?php

namespace App\Http\Controllers\Manager;

use App\Exports\ProjectExportExcel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Project;
use Illuminate\Support\Carbon;
use App\Statics\Statics;
use Maatwebsite\Excel\Facades\Excel;

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
        $projectsAssigned = Project::where('project_status', Statics::PROJECT_STATUS_ASSIGNED)->count();
        $projectsNotAssigned = Project::where('project_status', Statics::PROJECT_STATUS_NOT_ASSIGNED)->count();
        $projectsHold = Project::where('project_status', Statics::PROJECT_STATUS_ON_HOLD)->count();
        $projectsInProcess = Project::where('project_status', Statics::PROJECT_STATUS_IN_PROGRESS)->count();
        $projectsArchived = Project::where('project_status', Statics::PROJECT_STATUS_ARCHIVED)->count();
        $projectsCompleted = Project::where('project_status', Statics::PROJECT_STATUS_COMPLETED)->count();
        $projectsCancelled = Project::where('project_status', Statics::PROJECT_STATUS_CANCELLED)->count();
        $projectsMonthly = Project::latest()->paginate(5);
        $projectsWeekly = Project::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
        
        return view('manager.dashboard', compact('customerCount', 'engineerCount', 'projectsActive', 'projectsInActive', 'projectsMonthly', 'projectsWeekly', 'projectsAssigned', 'projectsNotAssigned', 'projectsHold', 'projectsInProcess', 'projectsArchived', 'projectsCompleted', 'projectsCancelled'));
    }

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

        return $projects;
    }

    public function exportExcel(Request $request){
        //dd($request->all());
        $projects = $this->projectMonthly($request);
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
