<?php

namespace App\Http\Controllers;

use App\Mail\Notification;
use App\User;
use App\Notifications\CustomerProject;
use App\Project;
use App\ProjectFile;
use App\ProjectType;
use App\SystemDesign;
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
        $fields['status'] = Statics::STATUS_ACTIVE;
        // $managers = User::whereHas(
        //     'roles', function($q){
        //         $q->where('name', 'manager');
        //     }
        // )->pluck('id');
        // foreach($managers as $manager){
        //     User::findOrFail($manager)->notify(new CustomerProject);
        // }

        // $admins = User::whereHas(
        //     'roles', function($q){
        //         $q->where('name', 'admin');
        //     }
        // )->pluck('id');
        // foreach($admins as $admin){
        //     User::findOrFail($admin)->notify(new CustomerProject);
        // }
        //return $engineers;

        //User::hasRole('admin')->notify(new CustomerProject);
        //return $admin;

        return Project::create($fields);
    }

    public function Bulkinsert(Request $request)
    {
        $project_ids=[];
        foreach($request['customer_project_type[]'] as $k=>$type)
        {
            $project=new Project;
            $project->name=$request['customer_name[]'][$k];
            $project->description="Bulk Project";
            $project->street_1=$request['customer_address[]'][$k];
            $project->customer_id=Auth::id();
            $project->company_id=(Auth::user()->role === Statics::USER_TYPE_ADMIN) ? null : Auth::user()->company_id;
            $project->project_type_id=ProjectType::where('name',$type)->first()->id;
            $project->save();
            array_push($project_ids,$project->id);
        }
        
        return $project_ids;
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


public function getProjectData(Request $request)
{

    if($request->ajax()) {
        if(Auth::user()->hasRole('customer'))
        $projectQuery = Auth::user()->projects()->with('type')->with('designs');
        else
        $projectQuery = Auth::user()->assignedProjects()->with('type')->with('designs');
        $term = trim($request->search);
        if ($term)
            $projectQuery->where('name', 'LIKE', '%' . $term . "%");
        $filters = json_decode($request->filters);
        foreach ($filters as $filter)
        {
        if($filter->field=='project_status')
        {
            if ($filter->value == 'completed')
            {
                $projectQuery->whereHas('designs', function($q){
                    $q->where('status_engineer','completed');
                });
            }
            elseif($filter->value == 'in progress')
            {
            $projectQuery->whereHas('designs', function($q){
                $q->where('status_engineer','in progress');
            });
            }
            elseif($filter->value == 'on hold')
            {
            $projectQuery->whereHas('designs', function($q){
                $q->where('status_engineer','on hold');
            });
            }
            elseif($filter->value == 'change request')
            {
            $projectQuery->whereHas('designs', function($q){
                $q->where('status_engineer','change request');
            });
            }
            
            elseif($filter->value == 'requested')
            {
            $projectQuery->whereHas('designs', function($q){
                $q->where('status_customer','requested');
            });
            }
            elseif($filter->value == 'assigned')
            {
            $projectQuery->whereHas('designs', function($q){
                $q->where('status_engineer','assigned');
            });
            }
            elseif($filter->value == 'not assigned')
            {
            $projectQuery->whereHas('designs', function($q){
                $q->where('status_engineer','not assigned');
            });
            }
            elseif($filter->value == 'received')
            {
            $projectQuery->whereHas('designs', function($q){
                $q->where('status_customer','received');
            });
            }
            elseif($filter->value == 'cancelled')
            {
            $projectQuery->whereHas('designs', function($q){
                $q->where('status_customer','cancelled');
            });
            }

   
        }
        elseif ($filter->value != 'all' )
                $projectQuery->where($filter->field, '=', $filter->value);
    } 

    $projectQuery=$projectQuery->latest()->paginate(5);
            return view('pages.project', compact('projectQuery'))->render();
    }
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

                $collection=collect();
                // foreach($query as $q)
                // {
                //     foreach($q->designs as $d)
                //     {
                //         $design_name=SystemDesign::findOrFail($d->id)->type->name;
                //         $collection->push([$q,$design_name]);
                //     }
                   
                // }
                // return $collection->all();
        return $query->latest()->paginate(5);
    }

    public function getFile(Request $request)
    {
        //return $request;
        $this->validate($request, [
            'project' => 'required|string|max:255',
            'file' => 'required|string|max:255'
        ]);
        $engineer = Project::findOrFail($request->project)->engineer;
        //return $engineer;
        if($engineer == ""){
            $file = ProjectFile::findOrFail($request->project);
            //return $file;
        }else{
            $project = $engineer->projects()->with('files')->where('id', $request->project)->firstOrFail();
            $file = $project->files->firstWhere('id', $request->file);
        }    
        
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
            if ($design->status_customer === Statics::DESIGN_STATUS_CUSTOMER_COMPLETED)
                $closedCount++;

        if ($closedCount !== sizeof($project->designs))
            $error = "Project not archived because it still has designs that are being worked on";
        else {
            $project->project_status = Statics::PROJECT_STATUS_ARCHIVED;
            $project->status=Statics::STATUS_IN_ACTIVE;
            $project->save();
        }

        return view('project.form', ["projectType" => $project->type, "project" => $project, 'fileTypes' => $project->files->groupBy('type.name'), "archive_error" => $error]);

    }

    public function bulkProject(){
        $project_id=Project::latest()->first()->id;

        return view('project.bulkProject',compact('project_id'));
    }
}
