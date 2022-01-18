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

    public function assign(Request $request)
    {
        //return $request;
        $project = Project::with('customer')->findOrFail($request->project_id);
        Project::where('id', $request->project_id)->update([
            "engineer_id" => $request->engineer_id,
            'status' => Statics::PROJECT_STATUS_ACTIVE
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
        $project->status = $request->statusName;
        $project->update();
        return back()->with('success', 'Status Updated!');
    }
}
