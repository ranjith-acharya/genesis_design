<?php

namespace App\Http\Controllers;

use App\DesignFile;
use App\User;

use App\Mail\Notification;
use App\Notifications\AuroraDesign;
use App\Notifications\DesignClose;
use App\Notifications\ElectricalLoadDesign;
use App\Notifications\EngineeringPermitDesign;
use App\Notifications\PEStampingDesign;
use App\Notifications\StructuralLoadDesign;
use App\ProjectFile;
use App\Statics\Statics;
use App\SystemDesign;
use App\Project;
use App\SystemDesignType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DesignRequestController extends Controller
{
    public function index($project_id)
    {
        $project = Auth::user()->projects()->with(['designs' => function ($query) {
            $query->select('id', 'price', 'project_id')->with('changeRequests')->withCount(['proposals']);
        }])->where('id', $project_id)->where('customer_id', Auth::id())->firstOrFail();

        $cost = 0;
        $designs = 0;
        $proposals = 0;
        $change_requests = 0;
        $change_request_cost = 0;
        $total = 0;
        foreach ($project->designs as $design) {
            $designs++;
            $cost += $design->price;
            $total += $design->price;
            $proposals += $design->proposals_count;
            foreach ($design->changeRequests as $changeRequest) {
                $change_requests++;
                if ($changeRequest->price) {
                    $total += $changeRequest->price;
                    $change_request_cost += $changeRequest->price;
                }
            }
        }

        return view('design.list', ["project" => $project, 'cost' => $cost, "designs" => $designs, "proposals" => $proposals, "change_requests" => $change_requests, "change_request_cost" => $change_request_cost, "total" => $total]);
    }

    public function form($project_id, $type)
    {
        $response = null;
        $type = str_replace('-', ' ', $type);
        $project_type = Project::findOrFail($project_id)->type->name;
        //return $project_type;
        switch ($type) {
            case (Statics::DESIGN_TYPE_AURORA):
                $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_AURORA)->firstOrFail();
                $response = view('design.forms.aurora', ["type" => $type, "project_id" => $project_id, "project_type" => $project_type]);
                break;
            case (Statics::DESIGN_TYPE_STRUCTURAL):
                $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_STRUCTURAL)->firstOrFail();
                $response = view('design.forms.structural-load-letter', ["type" => $type, "project_id" => $project_id, "project_type" => $project_type]);
                break;
            case (Statics::DESIGN_TYPE_PE):
                //return Statics::DESIGN_TYPE_PE;
                $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_PE)->firstOrFail();
                $response = view('design.forms.pe', ["type" => $type, "project_id" => $project_id]);
                break;
            case (Statics::DESIGN_TYPE_ELECTRICAL):
                $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_ELECTRICAL)->firstOrFail();
                $response = view('design.forms.electrical-load', ["type" => $type, "project_id" => $project_id]);
                break;
            case (Statics::DESIGN_TYPE_SITE_SURVEY):
                $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_SITE_SURVEY)->firstOrFail();
                $response = view('design.forms.site-survey', ["type" => $type, "project_id" => $project_id]);
                break;
            case (Statics::DESIGN_TYPE_ENGINEERING_PERMIT):
                $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_ENGINEERING_PERMIT)->firstOrFail();
                $response = view('design.forms.engineering-permit', ["type" => $type, "project_id" => $project_id, "project_type" => $project_type]);
                break;
            default:
                abort(404);
                break;
        }
        return $response;
    }

    public function attachFile(Request $request)
    {
        $this->validate($request, [
            'path' => 'required|string|max:255',
            'system_design_id' => 'required|numeric',
            'content_type' => 'required|string'
        ]);
        return DesignFile::create($request->all());
    }

    public function saveAurora(Request $request)
    {
        $this->validate($request, [
            "annual_usage" => "required|string|max:255",
            "module" => "required|string|max:255",
            "monitor" => "string|max:255",
            "racking" => "string|max:255",
            "inverter" => "required|string|max:255",
            "installation" => "required|string|max:255",
            "hoa" => "required|boolean",
            "max_offset" => "required|numeric",
            "project_id" => "required|numeric",
            "notes" => "required|max:500",
            "remarks" => "required|string|max:255",
            "system_size" => "required|string|max:255",
            "stripe_payment_code" => "required|string",
        ]);

        $project = Auth::user()->projects()->with('engineer')->where('id', $request->project_id)->first();

        if ($project && $project->status !== Statics::PROJECT_STATUS_ARCHIVED) {
            $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_AURORA)->first();
            $sd = new SystemDesign();
            $sd->system_design_type_id = $type->id;
            $sd->project_id = $project->id;
            $designs_count=Project::findOrFail($project->id)->designs->count();
            if($designs_count>0)
            {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_PROGRESS;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_PROGRESS;
            }
            else
            {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_REQUESTED;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED;
            }
            
            $sd->price = $type->latestPrice->price;
            $sd->fields = $request->only(["annual_usage", "installation", "hoa", "max_offset", "project_id", "notes", "remarks", "system_size", "module", "moduleType", "moduleOther", "racking", "rackingType", "rackingOther", "inverter", "inverterType", "inverterOther", "monitor", "monitorType", "monitorOther"]);
            $sd->stripe_payment_code = $request->stripe_payment_code;
            $sd->save();

            $managers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->pluck('id');
            foreach($managers as $manager){
                User::findOrFail($manager)->notify(new AuroraDesign($project->name, route('engineer.design.view', $sd->id)));
            }
            
            $allManagers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->get();
            //return $managers;
            foreach($allManagers as $allManager){
                Mail::to($allManager->email)
                ->send(new Notification($allManager->email,
                    "New Design request: " . $project->name,
                    "",
                    route('engineer.design.view', $sd->id),
                    "View Design"));
            }
    
            $admins = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'admin');
                }
            )->pluck('id');
            foreach($admins as $admin){
                User::findOrFail($admin)->notify(new AuroraDesign($project->name, route('engineer.design.view', $sd->id)));
            }


            return $sd;
        } else {
            abort(403);
            return false;
        }

    }

    public function saveStructuralLoad(Request $request)
    {
        $this->validate($request, [
            "fields" => "required",
            "project_id" => "required|numeric",
            'stripe_payment_code' => "required"
        ]);

        $project = Auth::user()->projects()->with('engineer')->where('id', $request->project_id)->first();

        if ($project && $project->status !== Statics::PROJECT_STATUS_ARCHIVED) {
            $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_STRUCTURAL)->first();
            $sd = new SystemDesign();
            $sd->system_design_type_id = $type->id;
            $sd->project_id = $project->id;
            $designs_count=Project::findOrFail($project->id)->designs->count();
            if($designs_count>0)
            {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_PROGRESS;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_PROGRESS;
            }
            else
            {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_REQUESTED;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED;
            }
            $sd->price = $type->latestPrice->price;
            $sd->fields = $request->fields;
            $sd->stripe_payment_code = $request->stripe_payment_code;
            $sd->save();

            $managers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->pluck('id');
            foreach($managers as $manager){
                User::findOrFail($manager)->notify(new StructuralLoadDesign($project->name, route('engineer.design.view', $sd->id)));
            }

            $allManagers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->get();
            //return $managers;
            foreach($allManagers as $allManager){
                Mail::to($allManager->email)
                ->send(new Notification($allManager->email,
                    "New Design request: " . $project->name,
                    "",
                    route('engineer.design.view', $sd->id),
                    "View Design"));
            }
    
            $admins = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'admin');
                }
            )->pluck('id');
            foreach($admins as $admin){
                User::findOrFail($admin)->notify(new StructuralLoadDesign($project->name, route('engineer.design.view', $sd->id)));
            }

            if ($project->engineer) {
                Mail::to($project->engineer->email)
                    ->send(new Notification($project->engineer->email, "New design request for: " . $project->name, "Type: " . Statics::DESIGN_TYPE_STRUCTURAL, route('engineer.design.view', $sd->id), "View Design"));
            }

            return $sd;
        } else {
            abort(403);
            return false;
        }

    }

    public function saveEngPermitPackage(Request $request)
    {
        //return $request;
       
        $project = Auth::user()->projects()->with('engineer')->where('id', $request->project_id)->first();  
        //return $project;
        if ($project && $project->status !== Statics::PROJECT_STATUS_ARCHIVED) {
            $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_ENGINEERING_PERMIT)->first();
            $sd = new SystemDesign();
            $sd->system_design_type_id = $type->id;
            $sd->project_id = $project->id;
            $designs_count=Project::findOrFail($project->id)->designs->count();
            if($designs_count>0)
            {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_PROGRESS;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_PROGRESS;
            }
            else
            {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_REQUESTED;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED;
            }
            $sd->price = $type->latestPrice->price;
            $sd->fields = $request->only(["system_size", "hoa", "installation", "remarks", "module", "moduleType", "moduleOther", "inverter", "inverterType", "inverterOther", "racking", "rackingType", "rackingOther", "monitor", "monitorType", "monitorOther", "utility", "tree_cutting", "re_roofing", "service_upgrade", "others", "array", "plywood", "osb", "skip_sheating", "plank", "roofDecking_LayerThickness", "center_spacing", "purlin", "pitch", "azimuth", "rafter_size", "roofMaterialOption", "other_roof_material", "soft_spots", "bouncy", "existing_leaks", "vaulted_ceiling", "comp_shingle_layers", "age_of_shingles", "roof_condition", "access_attic_vent", "stud_finder", "supply_side_voltage", "manufacturer_model", "main_breaker_rating", "busbar_rating", "meter_no", "proposed_point_connection", "meter_location", "tap_possible", "breaker_space", "grounding_method", "disconnect_type", "panel_location", "manufacturer_model1[]", "main_breaker_rating1[]", "busbar_rating1[]", "average_bill", "average_bill1"]);
            $sd->stripe_payment_code = $request->stripe_payment_code;
            $sd->save();

            $managers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->pluck('id');
            foreach($managers as $manager){
                User::findOrFail($manager)->notify(new EngineeringPermitDesign($project->name, route('engineer.design.view', $sd->id)));
            }
            
            $allManagers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->get();
            //return $managers;
            foreach($allManagers as $allManager){
                Mail::to($allManager->email)
                ->send(new Notification($allManager->email,
                    "New Design request: " . $project->name,
                    "",
                    route('engineer.design.view', $sd->id),
                    "View Design"));
            }

            $admins = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'admin');
                }
            )->pluck('id');
            foreach($admins as $admin){
                User::findOrFail($admin)->notify(new EngineeringPermitDesign($project->name, route('engineer.design.view', $sd->id)));
            }


            return $sd;
        } else {
            abort(403);
            return false;
        }
    }

    public function saveElectricalLoad(Request $request){
        $project = Auth::user()->projects()->with('engineer')->where('id', $request->project_id)->first();  
        //return $project;
        if ($project && $project->status !== Statics::PROJECT_STATUS_ARCHIVED) {
            $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_ELECTRICAL)->first();
            $sd = new SystemDesign();
            $sd->system_design_type_id = $type->id;
            $sd->project_id = $project->id;
            $designs_count=Project::findOrFail($project->id)->designs->count();
            if($designs_count>0)
            {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_PROGRESS;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_PROGRESS;
            }
            else
            {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_REQUESTED;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED;
            }
            $sd->price = $type->latestPrice->price;
            $sd->fields = $request->only(["average_bill", "average_bill1"]);
            $sd->stripe_payment_code = $request->stripe_payment_code;
            $sd->save();

            $managers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->pluck('id');
            foreach($managers as $manager){
                User::findOrFail($manager)->notify(new ElectricalLoadDesign($project->name, route('engineer.design.view', $sd->id)));
            }
    
            $allManagers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->get();
            //return $managers;
            foreach($allManagers as $allManager){
                Mail::to($allManager->email)
                ->send(new Notification($allManager->email,
                    "New Design request: " . $project->name,
                    "",
                    route('engineer.design.view', $sd->id),
                    "View Design"));
            }

            $admins = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'admin');
                }
            )->pluck('id');
            foreach($admins as $admin){
                User::findOrFail($admin)->notify(new ElectricalLoadDesign($project->name, route('engineer.design.view', $sd->id)));
            }


            return $sd;
        } else {
            abort(403);
            return false;
        }
    }

    public function savePEStamping(Request $request){
        $project = Auth::user()->projects()->with('engineer')->where('id', $request->project_id)->first();  
        //return $project;
        if ($project && $project->status !== Statics::PROJECT_STATUS_ARCHIVED) {
            $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_PE)->first();
            $sd = new SystemDesign();
            $sd->system_design_type_id = $type->id;
            $sd->project_id = $project->id;
            $designs_count=Project::findOrFail($project->id)->designs->count();
            if($designs_count>0)
            {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_PROGRESS;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_PROGRESS;
            }
            else
            {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_REQUESTED;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED;
            }
            $sd->price = $type->latestPrice->price;
            $sd->fields = $request->only(["structural_letter", "electrical_stamps"]);
            $sd->stripe_payment_code = $request->stripe_payment_code;
            $sd->save();

            $managers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->pluck('id');
            foreach($managers as $manager){
                User::findOrFail($manager)->notify(new PEStampingDesign($project->name, route('engineer.design.view', $sd->id)));
            }
            
            $allManagers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->get();
            //return $managers;
            foreach($allManagers as $allManager){
                Mail::to($allManager->email)
                ->send(new Notification($allManager->email,
                    "New Design request: " . $project->name,
                    "",
                    route('engineer.design.view', $sd->id),
                    "View Design"));
            }

            $admins = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'admin');
                }
            )->pluck('id');
            foreach($admins as $admin){
                User::findOrFail($admin)->notify(new PEStampingDesign($project->name, route('engineer.design.view', $sd->id)));
            }


            return $sd;
        } else {
            abort(403);
            return false;
        }
    }


    public function getDesignForms(Request $request)
    {
        $project_id=$request->project_id;
        $project_type=$request->project_type;
        if(count($request->designs)==1)
        {
            $type=$request->designs[0];
            switch ($type) {
                case (Statics::DESIGN_TYPE_AURORA):
                    $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_AURORA)->firstOrFail();
                    $response = view('design.forms.aurora', ["type" => $type, "project_id" => $project_id, "project_type" => $project_type]);
                    break;
                case (Statics::DESIGN_TYPE_STRUCTURAL):
                    $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_STRUCTURAL)->firstOrFail();
                    $response = view('design.forms.structural-load-letter', ["type" => $type, "project_id" => $project_id, "project_type" => $project_type]);
                    break;
                case (Statics::DESIGN_TYPE_PE):
                    //return Statics::DESIGN_TYPE_PE;
                    $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_PE)->firstOrFail();
                    $response = view('design.forms.pe', ["type" => $type, "project_id" => $project_id]);
                    break;
                case (Statics::DESIGN_TYPE_ELECTRICAL):
                    $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_ELECTRICAL)->firstOrFail();
                    $response = view('design.forms.electrical-load', ["type" => $type, "project_id" => $project_id]);
                    break;
                case (Statics::DESIGN_TYPE_SITE_SURVEY):
                    $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_SITE_SURVEY)->firstOrFail();
                    $response = view('design.forms.site-survey', ["type" => $type, "project_id" => $project_id]);
                    break;
                case (Statics::DESIGN_TYPE_ENGINEERING_PERMIT):
                    $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_ENGINEERING_PERMIT)->firstOrFail();
                    $response = view('design.forms.engineering-permit', ["type" => $type, "project_id" => $project_id, "project_type" => $project_type]);
                    break;
                default:
                    abort(404);
                    break;
            }
            return $response;
        }
        return view('design.forms.multipleDesign', ["type" => $request["designs"], "project_id" => $project_id, "project_type" => $project_type]);
       
    }

    //Store Multiple Designs 


    public function storeMultiple(Request $request){
        $designs=[];
        $project = Auth::user()->projects()->with('engineer')->where('id', $request->project_id)->first();

        if($request['stripe_payment_aurora']!="no")
        {
            $project = Auth::user()->projects()->with('engineer')->where('id', $request->project_id)->first();

        if ($project && $project->status !== Statics::PROJECT_STATUS_ARCHIVED) {
            $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_AURORA)->first();
            $sd = new SystemDesign();
            $sd->system_design_type_id = $type->id;
            $sd->project_id = $project->id;
            $designs_count=Project::findOrFail($project->id)->designs->count();
            // if($designs_count>0)
            // {
            //     $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_PROGRESS;
            //     $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_PROGRESS;
            // }
            // else
            // {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_REQUESTED;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED;
            // }
            
            $sd->price = $type->latestPrice->price;
            $sd->fields = $request->only(["annual_usage", "installation", "hoa", "max_offset", "project_id", "notes", "remarks", "system_size", "module", "moduleType", "moduleOther", "racking", "rackingType", "rackingOther", "inverter", "inverterType", "inverterOther", "monitor", "monitorType", "monitorOther"]);
            $sd->stripe_payment_code = $request->stripe_payment_aurora;
            $sd->save();
            array_push($designs,['name'=>'aurora','design_id'=>$sd->id]);
            //return $sd;
        } else {
            abort(403);
            return false;
        }

        }
        //storing strcutural load
        if($request['stripe_payment_structural']!="no")
        {
            $project = Auth::user()->projects()->with('engineer')->where('id', $request->project_id)->first();

            if ($project && $project->status !== Statics::PROJECT_STATUS_ARCHIVED) {
                $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_STRUCTURAL)->first();
                $sd = new SystemDesign();
                $sd->system_design_type_id = $type->id;
                $sd->project_id = $project->id;
                $designs_count=Project::findOrFail($project->id)->designs->count();
                // if($designs_count>0)
                // {
                //     $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_PROGRESS;
                //     $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_PROGRESS;
                // }
                // else
                // {
                    $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_REQUESTED;
                    $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED;
                // }
                $sd->price = $type->latestPrice->price;
                $sd->fields = $request->fields;
                $sd->stripe_payment_code = $request->stripe_payment_structural;
                $sd->save();
                //array_push($designs,['structural'=>$sd->id]);
                array_push($designs,['name'=>'structural','design_id'=>$sd->id]);
                //return $sd;
            }
                else {
                    abort(403);
                    return false;
                }
                
            }
            //storing PE Stamping
        if($request['stripe_payment_stamping']!="no")
        {

            $project = Auth::user()->projects()->with('engineer')->where('id', $request->project_id)->first();  
        //return $project;
        if ($project && $project->status !== Statics::PROJECT_STATUS_ARCHIVED) {
            $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_PE)->first();
            $sd = new SystemDesign();
            $sd->system_design_type_id = $type->id;
            $sd->project_id = $project->id;
            $designs_count=Project::findOrFail($project->id)->designs->count();
            // if($designs_count>0)
            // {
            //     $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_PROGRESS;
            //     $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_PROGRESS;
            // }
            // else
            // {
                $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_REQUESTED;
                $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED;
            // }
            $sd->price = $type->latestPrice->price;
            $sd->fields = $request->only(["structural_letter", "electrical_stamps"]);
            $sd->stripe_payment_code = $request->stripe_payment_stamping;
            $sd->save();
            //array_push($designs,['stamps'=>$sd->id]);
            array_push($designs,['name'=>'stamps','design_id'=>$sd->id]);
            //return $sd;
        }
            else {
                abort(403);
                return false;
            }
        }



        //store Engineering Permit Package
        if($request['stripe_payment_permit']!="no")
        {

            $project = Auth::user()->projects()->with('engineer')->where('id', $request->project_id)->first();  
            //return $project;
            if ($project && $project->status !== Statics::PROJECT_STATUS_ARCHIVED) {
            $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_ENGINEERING_PERMIT)->first();
            $sd = new SystemDesign();
            $sd->system_design_type_id = $type->id;
            $sd->project_id = $project->id;
            $designs_count=Project::findOrFail($project->id)->designs->count();
            
            $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_REQUESTED;
            $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED;
            
            $sd->price = $type->latestPrice->price;
            $sd->fields = $request->only(["system_size", "hoa", "installation", "remarks", "module", "moduleType", "moduleOther", "inverter", "inverterType", "inverterOther", "racking", "rackingType", "rackingOther", "monitor", "monitorType", "monitorOther", "utility", "tree_cutting", "re_roofing", "service_upgrade", "others", "array", "plywood", "osb", "skip_sheating", "plank", "roofDecking_LayerThickness", "center_spacing", "purlin", "pitch", "azimuth", "rafter_size", "roofMaterialOption", "other_roof_material", "soft_spots", "bouncy", "existing_leaks", "vaulted_ceiling", "comp_shingle_layers", "age_of_shingles", "roof_condition", "access_attic_vent", "stud_finder", "supply_side_voltage", "manufacturer_model", "main_breaker_rating", "busbar_rating", "meter_no", "proposed_point_connection", "meter_location", "tap_possible", "breaker_space", "grounding_method", "disconnect_type", "panel_location", "manufacturer_model1[]", "main_breaker_rating1[]", "busbar_rating1[]", "average_bill", "average_bill1"]);
            $sd->stripe_payment_code = $request->stripe_payment_permit;
            $sd->save();
            array_push($designs,['name'=>'permit','design_id'=>$sd->id]);
            }
            else {
                abort(403);
                return false;
            }
        }

        //store ELectrical Load

        if($request['stripe_payment_electrical']!="no")
        {

            $project = Auth::user()->projects()->with('engineer')->where('id', $request->project_id)->first();  
            //return $project;
            if ($project && $project->status !== Statics::PROJECT_STATUS_ARCHIVED) {
                $type = SystemDesignType::with('latestPrice')->where('name', Statics::DESIGN_TYPE_ELECTRICAL)->first();
                $sd = new SystemDesign();
                $sd->system_design_type_id = $type->id;
                $sd->project_id = $project->id;
                $designs_count=Project::findOrFail($project->id)->designs->count();
                // if($designs_count>0)
                // {
                //     $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_PROGRESS;
                //     $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_PROGRESS;
                // }
                // else
                // {
                    $sd->status_customer = Statics::DESIGN_STATUS_CUSTOMER_REQUESTED;
                    $sd->status_engineer = Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED;
                // }
                $sd->price = $type->latestPrice->price;
                $sd->fields = $request->only(["average_bill", "average_bill1"]);
                $sd->stripe_payment_code = $request->stripe_payment_electrical;
                $sd->save();
               // array_push($designs,['aurora'=>$sd->id]);
                //return $sd;
            }
                else {
                    abort(403);
                    return false;
                }
        }

        //portal Notification
        //mail Notifications

        $allManagers = User::whereHas(
            'roles', function($q){
                $q->where('name', 'manager');
            }
        )->get();
        //return $managers;
        foreach($allManagers as $allManager){
            Mail::to($allManager->email)
            ->send(new Notification($allManager->email,
                "New Design request: " . $project->name,
                "",
                route('engineer.design.view', $sd->id),
                "View Design"));
        }
        
        return $designs;
    }

    public function getDesigns(Request $request)
    {

        $this->validate($request, [
            "id" => "required|numeric"
        ]);
        $project = Auth::user()->projects()->where('id', $request->id)->where('customer_id', Auth::id())->firstOrFail();

        if ($project) {

            $query = $project->designs()->with('type')->withCount('proposals');

            $term = trim($request->search);
            if ($term)
                $query->where('name', 'LIKE', '%' . $term . "%");

            $filters = json_decode($request->filters);
            foreach ($filters as $filter)
                if ($filter->value != 'all')
                    $query->where($filter->field, '=', $filter->value);

            return $query->latest()->paginate(5);
        } else {
            abort(403);
            return false;
        }
    }

    public function view($id)
    {
        $design = Auth::user()->designs()->with(['project', 'type', 'files', 'proposals' => function ($query) {
            $query->withCount('changeRequest')->latest();
        }])->where('system_designs.id', $id)->firstOrFail();
        return view('design.design_main', ["design" => $design]);
    }

    public function getFile(Request $request)
    {
        //return $request;
        $this->validate($request, [
            'file' => 'required|string|max:255',
            'design' => 'required|string|max:255'
        ]);
        

        
        $engineer = SystemDesign::findOrFail($request->design)->project->engineer;
        //return $engineer;
        if($engineer == ""){
            $file = DesignFile::findOrFail($request->file);
            //return $design;
        }else{
            $design = $engineer->designs()->with('files')->where('system_designs.id', $request->design)->firstOrFail();
        
            $file = null;
            foreach ($design->files as $f)
                if ($f->id == $request->file)
                    $file = $f;
        }
        
        if ($file) {
            $url = Http::get(env('SUN_STORAGE') . "/file/url?ttl_seconds=900&api-key=" . env('SUN_STORAGE_KEY') . "&file_path=" . $file->path)->body();
            return redirect()->away(json_decode($url));
        }

        abort(404);
        return null;
    }

    public function closeDesignRequest($id)
    {   //return $id;
        $design = Auth::user()->designs()->findOrFail($id);
        if ($design->payment_date != null) {
            $design->status_customer = Statics::DESIGN_STATUS_CUSTOMER_COMPLETED;
            $design->status_engineer = Statics::DESIGN_STATUS_ENGINEER_COMPLETED;
            $design->project->project_status = Statics::PROJECT_STATUS_COMPLETED;
            $design->project->status = Statics::STATUS_IN_ACTIVE;
            $design->project->save();
            $design->save();

            $admin = User::where('role', 'admin')->first();
            $admin->notify(new DesignClose($design->project->name, route('engineer.design.view', $design->id)));

            $managers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->pluck('id');
            foreach($managers as $manager){
                User::findOrFail($manager)->notify(new DesignClose($design->project->name, route('engineer.design.view', $design->id)));
            }
            
            $allManagers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->get();
            foreach($allManagers as $allManager){
                Mail::to($allManager->email)
                    ->send(new Notification($allManager->email,
                        "Design Closed for: " . $design->project->name,
                        "",
                        route('engineer.design.view', $design->id),
                        "View Change Request"));
            }

            User::findOrFail($design->project->engineer->id)->notify(new DesignClose($design->project->name, route('engineer.design.view', $design->id)));

            Mail::to($design->project->engineer->email)
                ->send(new Notification($design->project->engineer->email,
                    "Design Closed for: " . $design->project->name,
                    "",
                    route('engineer.design.view', $design->id),
                    "View Change Request"));
            
            Mail::to(User::where('role', 'admin')->first()->email)
                ->send(new Notification($design->project->engineer->email,
                    "Design Closed for: " . $design->project->name,
                    "",
                    route('engineer.design.view', $design->id),
                    "View Change Request"));

            return response()->redirectToRoute('design.view', $design->id);
        }
        return abort(402, "Design not paid for");
    }
}