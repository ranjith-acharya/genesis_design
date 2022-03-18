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
        
        $auroraDesignCount = SystemDesign::where('system_design_type_id', 1)->count();
        $structuralCount = SystemDesign::where('system_design_type_id', 2)->count();
        $pestampingCount = SystemDesign::where('system_design_type_id', 3)->count();
        $electricalCount = SystemDesign::where('system_design_type_id', 4)->count();
        $permitCount = SystemDesign::where('system_design_type_id', 6)->count();

        $projects = Project::latest()->paginate(5);
        $projectsWeekly = Project::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
        //return Carbon::now()->subWeek()->endOfWeek();
        return view('admin.home', compact('customerCount', 'engineerCount', 'projectsActive', 'projectsInActive', 'projects', 'projectsWeekly', 'designsAssigned', 'designsNotAssigned', 'projectsHold', 'designsInProcess', 'projectsArchived', 'designsCompleted', 'projectsCancelled', 'auroraDesignCount', 'structuralCount', 'pestampingCount', 'electricalCount', 'permitCount'));
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
        $status = $request->get('state');
        $project_status=$request->get('status');

        if($startDate != "" && $endDate != "" && $status != ""){
            $projects = Project::where('status', $status)
                                    ->whereBetween('created_at', [ $startDate, $endDate ]);
        }elseif($startDate != "" && $endDate != "" && $status == ""){
            $projects = Project::whereBetween('created_at', [ $startDate, $endDate ]);
        }elseif($status != "" && $startDate == "" && $endDate == ""){
            $projects = Project::where('status', $status);
        }
            else
            {
                $projects=Project::latest();
            } 
            if ($project_status == 'completed')
            {
                $projects->whereHas('designs', function($q){
                    $q->where('status_engineer','completed');
                });
            }
            elseif($project_status == 'in progress')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','in progress');
            });
            }
            elseif($project_status == 'on hold')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','on hold');
            });
            }
            elseif($project_status == 'change request')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','change request');
            });
            }
            elseif($project_status == 'assigned')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','assigned');
            });
            }
            elseif($project_status == 'not assigned')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','not assigned');
            });
            }
            elseif($project_status == 'submitted')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','submitted');
            });
            }
            elseif($project_status == 'cancelled')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','cancelled');
            });
            }

                $projects=$projects->get();

        return view('admin.reports.monthlyData', compact('projects'))->render();
    }
    public function projectData(Request $request){
       
        $startDate =  $request->get('from_date');
        $endDate   = $request->get('to_date');
        $status = $request->get('state');
        $project_status=$request->get('status');

        if($startDate != "" && $endDate != "" && $status != ""){
            $projects = Project::where('status', $status)
                                    ->whereBetween('created_at', [ $startDate, $endDate ]);
        }elseif($startDate != "" && $endDate != "" && $status == ""){
            $projects = Project::whereBetween('created_at', [ $startDate, $endDate ]);
        }elseif($status != "" && $startDate == "" && $endDate == ""){
            $projects = Project::where('status', $status);
        }
            else
            {
                $projects=Project::latest();
            } 
            if ($project_status == 'completed')
            {
                $projects->whereHas('designs', function($q){
                    $q->where('status_engineer','completed');
                });
            }
            elseif($project_status == 'in progress')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','in progress');
            });
            }
            elseif($project_status == 'on hold')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','on hold');
            });
            }
            elseif($project_status == 'change request')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','change request');
            });
            }
            elseif($project_status == 'assigned')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','assigned');
            });
            }
            elseif($project_status == 'not assigned')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','not assigned');
            });
            }
            elseif($project_status == 'submitted')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','submitted');
            });
            }
            elseif($project_status == 'cancelled')
            {
            $projects->whereHas('designs', function($q){
                $q->where('status_engineer','cancelled');
            });
            }
            $projects=$projects->get();
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
