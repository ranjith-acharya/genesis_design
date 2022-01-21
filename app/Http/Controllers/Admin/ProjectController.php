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

    public function archiveAll(Request $request)
    {
        return $request;
        foreach($request->project_ids as $project_id)
        {
            $project = Project::findOrFail($project_id);
            $project->project_status=Statics::PROJECT_STATUS_ARCHIVED;
            $project_id->status=Statics::STATUS_IN_ACTIVE;
            $project->save();
        }
        return "Archive Successfully";
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

    public function assign(Request $request)
    {
        //return $request;
        $project = Project::with('customer')->findOrFail($request->project_id);
        Project::where('id', $request->project_id)->update([
            "engineer_id" => $request->engineer_id,
            'status' => Statics::STATUS_ACTIVE,
            'project_status' => Statics::PROJECT_STATUS_ASSIGNED,
            'assigned_date' => Carbon::now(),
        ]);

//        email customer that project is being worked on
//        Mail::to($project->customer->email)
//            ->send(new Notification($project->customer->email, "Project Status Update", "Your project <b>$project->name</b> is now being worked on!", route('project.edit', $project->id), "View Project"));


        return back()->with('success', 'Project assigned successfully!');
    }
    public function getAssignEngineer($id)
    {
        $project = Project::findOrFail($id);
        return $project;
    }

    public function setStatus(Request $request){
        //return $request;
        $project = Project::findOrFail($request->projectId);
        $project->project_status = $request->statusName;
        if($request->statusName == Statics::PROJECT_STATUS_NOT_ASSIGNED || $request->statusName == Statics::PROJECT_STATUS_CANCELLED || $request->statusName == Statics::PROJECT_STATUS_ARCHIVED){
            $project->status = Statics::STATUS_IN_ACTIVE;
        }else{
            $project->status = Statics::STATUS_ACTIVE;
        }
        $project->update();
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
