<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Project;
use App\User;
use App\ProjectFile;
use App\ProjectType;
use App\Statics\Statics;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Mail\Notification;
use App\Notifications\ManagerAssign;
use App\Notifications\StatusChange;
use App\Notifications\StatusHold;
use Illuminate\Support\Facades\Mail;
use App\SystemDesign;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return 'hello';
        $projectQuery = Project::all();
        $engineers = User::where('role', 'engineer')->get();
        return view('admin.project.index', compact('projectQuery', 'engineers'));
    }

    public function indexProject(){
        $projectQuery = Project::latest()->get();
        $engineers = User::where('role', 'engineer')->get();
        return view('admin.project.index', compact('projectQuery', 'engineers'));
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
        $project = Project::with(['type', 'files.type'])->findOrFail($id);
       // return view('admin.project.edit', compact('projectQuery', 'id'));
       // $project = Auth::user()->projects()->with(['type', 'files.type'])->findOrFail($id);
        return view('admin.project.edit', ["projectType" => $project->type, "project" => $project, 'fileTypes' => $project->files->groupBy('type.name')]);
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

    public function attachFile(Request $request)
    {
        $this->validate($request, [
            'file_type_id' => 'required|numeric',
            'path' => 'required|string|max:255',
            'project_id' => 'required|numeric',
            'content_type' => 'required|string'
        ]);
        return ProjectFile::create($request->all());
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



    public function getProjects(Request $request)
    {
        $query = Project::latest();
        // $term = trim($request->search);
        // if ($term)
        //     $query->where('name', 'LIKE', '%' . $term . "%");

        // $filters = json_decode($request->filters);
        // foreach ($filters as $filter)
        //     if ($filter->value != 'all')
        //         $query->where($filter->field, '=', $filter->value);

        return $query->paginate(5);
    }


    public function getProjectData(Request $request)
{

    if($request->ajax()) {
        $projectQuery = Project::with('type')->with('designs');

        $term = trim($request->search);
        if ($term)
            $projectQuery->where('name', 'LIKE', '%' . $term . "%");
        $filters = json_decode($request->filters);
        foreach ($filters as $filter)
                if($filter->field=='project_status')
                    $projectQuery->with('designs')->where('status_customer',$filter->value);
                if ($filter->value != 'all' && $filter->field!='project_status' )
                    $projectQuery->where($filter->field, '=', $filter->value);
    $projectQuery=$projectQuery->latest()->paginate(3);
            return view('pages.project', compact('projectQuery'))->render();
    }
}
    public function assign(Request $request)
    {
        $user_name=Auth::user()->first_name;
        $project = Project::with('customer')->findOrFail($request->project_id);
        
        Project::where('id', $request->project_id)->update([
            "engineer_id" => $request->engineer_id,
            'status' => Statics::STATUS_ACTIVE,
            'project_status' => Statics::PROJECT_STATUS_ASSIGNED,
            'assigned_date' => Carbon::now(),
        ]);
        $system_design=SystemDesign::findOrFail($request->design_id);
        $system_design->status_customer = Statics::DESIGN_STATUS_CUSTOMER_PROGRESS;
        $system_design->status_engineer = Statics::DESIGN_STATUS_ENGINEER_ASSIGNED;
        $system_design->save();
        
        User::findOrFail($request->engineer_id)->notify(new ManagerAssign($project->name, route('engineer.project.view', $project->id)));
        User::findOrFail($project->customer->id)->notify(new ManagerAssign($project->name, route('project.edit', $project->id)));

        Mail::to($project->customer->email)
            ->send(new Notification($project->customer->email, "Project Status Update", "Your project <b>$project->name</b> is now being worked on!", route('project.edit', $project->id), "View Project"));
        
        $project=Project::findOrFail($request->project_id);
        Mail::to($project->engineer->email)
            ->send(new Notification($project->engineer->email, "Project Status Update", "You have been assigned on this Project: <b>$project->name</b> By <br><b>$user_name</b>!", route('engineer.project.view', $project->id), "View Project"));


        return back()->with('success', 'Project assigned successfully!');
    }
    public function getAssignEngineer($id)
    {
        $project = Project::findOrFail($id);
        return $project;
    }

    public function setStatus(Request $request){
        //return $request;
        $design = SystemDesign::findOrFail($request->designId);
        //return $design->project->project_status;
        $design->status_engineer = $request->statusName;
        $design->status_customer = $request->statusName;
        $design->note = $request->statusNote;
        if($request->statusName == Statics::DESIGN_STATUS_ENGINEER_HOLD){
            $design->status_engineer = Statics::DESIGN_STATUS_ENGINEER_HOLD;
            $design->status_customer = Statics::DESIGN_STATUS_CUSTOMER_HOLD;
        }else{
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
            User::findOrFail($manager)->notify(new StatusChange($design->type->name, route('engineer.design.view', $design->id)));
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
                "New update on your design: " . $design->type->name,
                "",
                route('engineer.design.view', $design->id),
                "View Design"));
        }

        User::findOrFail($design->project->customer->id)->notify(new StatusChange($design->type->name, route('design.view', $design->id)));
        Mail::to($design->project->customer->email)
            ->send(new Notification($design->project->customer->email,
                "New update on your design: " . $design->type->name,
                "",
                route('design.view', $design->id),
                "View Design"));
        
        
        return back()->with('success', 'Status Updated!');
    }

    public function getFile(Request $request)
    {
        $this->validate($request, [
            'project' => 'required|string|max:255',
            'file' => 'required|string|max:255'
        ]);

        $project = Auth::user()->projects()->with('files')->where('id', $request->project)->firstOrFail();
        $file = $project->files->firstWhere('id', $request->file);
        if ($file) {
            $url = Http::get(env('SUN_STORAGE') . "/file/url?ttl_seconds=900&api-key=" . env('SUN_STORAGE_KEY') . "&file_path=" . $file->path)->body();
            return redirect()->away(json_decode($url));
        } else {
            abort(404);
            return false;
        }
    }
}
