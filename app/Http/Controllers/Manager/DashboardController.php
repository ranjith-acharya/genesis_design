<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Project;
use Illuminate\Support\Carbon;

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
        $projectsPending = Project::where('status', 'pending')->count();
        $projectsMonthly = Project::whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
        $projectsWeekly = Project::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
        return view('manager.dashboard', compact('customerCount', 'engineerCount', 'projectsActive', 'projectsPending', 'projectsMonthly', 'projectsWeekly'));
    }

    public function projectMonthly(Request $request){
        $startDate = $request->get('from_date');
        $endDate = $request->get('to_date');
        $status = $request->get('status');
        if($startDate == "" && $endDate == "" && $status == ""){
            return Project::whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
        }elseif($startDate == "" || $endDate == ""){
            return Project::where('status', $status)->get();
        }elseif($status == ""){
            return Project::whereBetween('created_at', [ $startDate, $endDate ])->get();
        }else{
            return Project::where('status', $status)
                            ->whereBetween('created_at', [ $startDate, $endDate ])->get();
        }
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
