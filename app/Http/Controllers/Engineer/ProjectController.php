<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use App\Mail\Notification;
use App\Notifications\StatusChange;
use App\Project;
use App\Statics\Statics;
use App\SystemDesign;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    public function getProjects(Request $request)
    {
        $query = Auth::user()->assignedProjects()->with('type');

        $term = trim($request->search);
        if ($term)
            $query->where('name', 'LIKE', '%' . $term . "%");

        $filters = json_decode($request->filters);
        foreach ($filters as $filter)
            if ($filter->value != 'all')
                $query->where($filter->field, '=', $filter->value);

        return $query->latest()->paginate(5);
    }

    public function availableProjects()
    {
        $projects = Project::with('type')->where('engineer_id', null)->get();
        return view('engineer.project.available', ['projects' => $projects]);
    }

    public function assign($id)
    {
        $project = Project::with('customer')->findOrFail($id);
        Project::where('id', $id)->update([
            "engineer_id" => Auth::id(),
            'status' => Statics::STATUS_ACTIVE
        ]);

//        email customer that project is being worked on
//        Mail::to($project->customer->email)
//            ->send(new Notification($project->customer->email, "Project Status Update", "Your project <b>$project->name</b> is now being worked on!", route('project.edit', $project->id), "View Project"));


        return response()->redirectToRoute('home');
    }

    public function view($id)
    {
        $project = Auth::user()->assignedProjects()->with(['type', 'files.type'])->findOrFail($id);
        return view('engineer.project.view', ["projectType" => $project->type, "project" => $project, 'fileTypes' => $project->files->groupBy('type.name')]);
    }

    public function setStatus(Request $request){
        $design = SystemDesign::findOrFail($request->designId);
        //return $design;
        
        $design->note = $request->statusNote;
        if($request->statusName == Statics::DESIGN_STATUS_ENGINEER_HOLD){
            $design->start_date = Carbon::now();
            $design->status_engineer = Statics::DESIGN_STATUS_ENGINEER_HOLD;
            $design->status_customer = Statics::DESIGN_STATUS_CUSTOMER_HOLD;
        }else{
            $design->start_date = Carbon::now();
            $design->status_engineer = Statics::DESIGN_STATUS_ENGINEER_PROGRESS;
            $design->status_customer = Statics::DESIGN_STATUS_CUSTOMER_PROGRESS;
        }
        $design->update();
        $managers = User::whereHas(
            'roles', function($q){
                $q->where('name', 'manager');
            }
        )->pluck('id');
        //return $managers;
        foreach($managers as $manager){
            User::findOrFail($manager)->notify(new StatusChange(ucwords(strtolower($design->type->name)), route('engineer.design.view', $design->id)));
        }

        $allManagers = User::whereHas(
            'roles', function($q){
                $q->where('name', 'manager');
            }
        )->get();
        //return $managers;
        foreach($allManagers as $allManager){
            Mail::to($allManager->email)
            ->send(new Notification($design->project->customer->email,
                "New update on design: " . ucwords(strtolower($design->type->name)),
                "",
                route('engineer.design.view', $design->id),
                "View Design"));
        }

        User::findOrFail($design->project->customer->id)->notify(new StatusChange(ucwords(strtolower($design->type->name)), route('design.view', $design->id)));
        Mail::to($design->project->customer->email)
            ->send(new Notification($design->project->customer->email,
                "New update on your design: " .ucwords(strtolower($design->type->name)),
                "",
                route('design.view', $design->id),
                "View Design"));
        
        
        return back()->with('success', 'Status Updated!');
    }
}
