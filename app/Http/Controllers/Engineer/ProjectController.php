<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use App\Mail\Notification;
use App\Project;
use App\Statics\Statics;
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
            'status' => Statics::PROJECT_STATUS_ACTIVE
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
}
