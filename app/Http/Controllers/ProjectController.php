<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectFile;
use App\ProjectType;
use App\Statics\Statics;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProjectController extends Controller
{
    public function form($type)
    {
        $type = ProjectType::with('fileCategories')->where('name', $type)->where('is_hidden', '=', 0)->firstOrFail();
        return view('project.form')->with("projectType", $type);
    }

    public function edit($id)
    {
        $project = Auth::user()->projects()->with(['type', 'files.type'])->findOrFail($id);
        return view('project.form', ["projectType" => $project->type, "project" => $project, 'fileTypes' => $project->files->groupBy('type.name')]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'street_1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'project_type_id' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255'
        ]);
        $fields = $request->all();
        $fields['customer_id'] = Auth::id();
        $fields['company_id'] = (Auth::user()->role === Statics::USER_TYPE_ADMIN) ? null : Auth::user()->company_id;

        return Project::create($fields);
    }

    public function Bulkinsert(Request $request)
    {
        
        $fields = $request->all();
        $fields['customer_id'] = Auth::id();
        $fields['company_id'] = (Auth::user()->role === Statics::USER_TYPE_ADMIN) ? null : Auth::user()->company_id;

        return Project::create($fields);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'street_1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'project_id' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255'
        ]);
        $project = Auth::user()->projects()->with('files')->where('id', $request->project_id)->where('status', Statics::PROJECT_STATUS_PENDING)->firstOrFail();

        if ($project->status !== Statics::PROJECT_STATUS_ARCHIVED) {
            $project->name = $request->name;
            $project->description = $request->description;
            $project->street_1 = $request->street_1;
            $project->city = $request->city;
            $project->state = $request->state;
            $project->zip = $request->zip;
            $project->country = $request->country;
            $project->latitude = $request->latitude;
            $project->longitude = $request->longitude;
            return $project->update();
        } else
            return abort(400, "Project archived. Cannot update");
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

    public function getProjects(Request $request)
    {
        $query = Auth::user()->projects()->with('type');  
        $term = trim($request->search);
        if ($term)
            $query->where('name', 'LIKE', '%' . $term . "%");

        $filters = json_decode($request->filters);
        foreach ($filters as $filter)
            if ($filter->value != 'all')
                $query->where($filter->field, '=', $filter->value);

        return $query->latest()->paginate(5);
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

    public function archive($id)
    {

        $project = Project::with(['type', 'files.type', 'designs'])->findOrFail($id);

        $closedCount = 0;
        $error = null;
        foreach ($project->designs as $design)
            if ($design->status === Statics::DESIGN_STATUS_COMPLETED)
                $closedCount++;

        if ($closedCount !== sizeof($project->designs))
            $error = "Project not archived because it still has designs that are being worked on";
        else {
            $project->status = Statics::PROJECT_STATUS_ARCHIVED;
            $project->save();
        }

        return view('project.form', ["projectType" => $project->type, "project" => $project, 'fileTypes' => $project->files->groupBy('type.name'), "archive_error" => $error]);

    }

    public function bulkProject(){
        return view('project.bulkproject');
    }
}
