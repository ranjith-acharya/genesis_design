<?php

namespace App\Http\Controllers;

use App\ChangeRequest;
use App\ChangeRequestFile;
use App\Mail\Notification;
use App\Notifications\AdminQuote;
use App\Notifications\ChangeRequestCustomer;
use App\Notifications\CustomerAcceptQuote;
use App\Notifications\CustomerRejectQuote;
use App\Statics\Statics;
use App\SystemDesign;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;



class ChangeRequestController extends Controller
{
    public function insert(Request $request)
    {
        $this->validate($request, [
            "description" => 'required',
            "proposal" => 'required',
            "design" => "required"
        ]);

        $design = Auth::user()->designs()->with(['project.engineer', 'proposals' => function ($query) use ($request) {
            $query->with('changeRequest')->findOrFail($request->proposal);
        }])->where('system_designs.id', $request->design)->firstOrFail();

        $system_design=SystemDesign::findOrFail($request->design);
        
        $system_design->status_customer = Statics::DESIGN_STATUS_CUSTOMER_CHANGE_REQUEST;
        $system_design->status_engineer = Statics::DESIGN_STATUS_ENGINEER_CHANGE_REQUEST;
        $system_design->project->status= Statics::STATUS_ACTIVE;
         $system_design->project->save();
        $system_design->save();
        if (!$design->proposals[0]->changeRequest) {
            $cr = new ChangeRequest();
            $cr->description = $request->description;
//            $cr->system_design_id = $design->id;
            $cr->proposal_id = $request->proposal;
            $cr->save();

            $admin = User::where('role', 'admin')->first();
            $admin->notify(new ChangeRequestCustomer($design->project->name, route('proposal.view', $design->id) . "?proposal=" . $request->proposal));

            Mail::to(User::where('role', 'admin')->first()->email)
                ->send(new Notification(User::where('role', 'admin')->first()->email,
                    "New change request for: " . $design->project->name,
                    "",
                    route('proposal.view', $design->id) . "?proposal=" . $request->proposal,
                    "View Proposal"));

            return $cr;
        } else {
            return abort(400, "Proposal already has change request");
        }
    }

    public function attachFile(Request $request)
    {
        $this->validate($request, [
            'path' => 'required|string|max:255',
            'change_request_id' => 'required|numeric',
            'content_type' => 'required|string'
        ]);
        return ChangeRequestFile::create($request->all());
    }

    public function getFile(Request $request)
    {
        //return $request;
        $this->validate($request, [
            'design' => 'required|numeric',
            'changeRequest' => 'required|numeric',
            'file' => 'required|numeric'
        ]);

        $engineer = SystemDesign::findOrFail($request->design)->project->engineer;
        //return $engineer;
        $design = $engineer->designs()->with(['changeRequests' => function ($query) use ($request) {
            $query->findOrFail($request->changeRequest);
        }])->where('system_designs.id', $request->design)->firstOrFail();

        $file = $design->changeRequests[0]->files->firstWhere('id', $request->file);
        if ($file) {
            $url = Http::get(env('SUN_STORAGE') . "/file/url?ttl_seconds=900&api-key=" . env('SUN_STORAGE_KEY') . "&file_path=" . $file->path)->body();
            return redirect()->away(json_decode($url));
        } else {
            abort(404);
            return false;
        }
    }

    public function quote(Request $request)
    {
        $this->validate($request, [
            "quote" => 'required',
            "change_request_id" => 'required',
            "design" => "required",
            "approve" => 'required|boolean'
        ]);
        $engineer = SystemDesign::findorFail($request->design)->project->engineer;
        //return $engineer;
        $design = $engineer->designs()->with(['project.customer', 'type', 'changeRequests' => function ($query) use ($request) {
            $query->findOrFail($request->change_request_id);
        }])->where('system_designs.id', $request->design)->firstOrFail();
        $cr = $design->changeRequests[0];
       
        if ($request->approve){
            $cr->price = $request->quote;
            $cr->status = Statics::CHANGE_REQUEST_STATUS_AWAITING_APPROVAL;
            // $design->status_customer = Statics::DESIGN_STATUS_CUSTOMER_ACCEPTED;
            // $design->status_engineer = Statics::DESIGN_STATUS_ENGINEER_ACCEPTED;
            // $design->save();
        }else{
            $cr->price = null;
            $cr->status = Statics::CHANGE_REQUEST_STATUS_REJECTED;

            $design->status_customer = Statics::DESIGN_STATUS_CUSTOMER_COMPLETED;
            $design->status_engineer = Statics::DESIGN_STATUS_ENGINEER_COMPLETED;
            $design->end_date=Carbon::now();
            $design->save();
        }

        $cr->engineer_note = $request->note;
        $cr->save();

        User::findOrFail($design->project->customer->id)->notify(new AdminQuote($design->project->name, route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id));

        Mail::to($design->project->customer->email)
            ->send(new Notification($design->project->customer->email,
                "New update for your change request for: " . $design->project->name,
                "",
                route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id,
                "View Change Request"));

        return $cr;
    }

    public function accept(Request $request)
    {

        $this->validate($request, [
            "stripe_payment_code" => 'required',
            "change_request_id" => 'required',
            "design_id" => "required"
        ]);

        $design = Auth::user()->designs()->with(['project.engineer', 'type', 'changeRequests' => function ($query) use ($request) {
            $query->findOrFail($request->change_request_id);
        }])->where('system_designs.id', $request->design_id)->firstOrFail();

        $cr = $design->changeRequests[0];
        $cr->stripe_payment_code = $request->stripe_payment_code;
        $cr->status = Statics::CHANGE_REQUEST_STATUS_APPROVED;
        $cr->save();

        $admin = User::where('role', 'admin')->first();
        $admin->notify(new CustomerAcceptQuote($design->project->name, route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id));

        User::findOrFail($design->project->engineer->id)->notify(new CustomerAcceptQuote($design->project->name, route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id));
        
        $managers = User::whereHas(
            'roles', function($q){
                $q->where('name', 'manager');
            }
        )->pluck('id');
        foreach($managers as $manager){
            User::findOrFail($manager)->notify(new CustomerAcceptQuote($design->project->name, route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id));
        }

        $allmanagers = User::whereHas(
            'roles', function($q){
                $q->where('name', 'manager');
            }
        )->get();
        foreach($allmanagers as $allmanager){
            Mail::to($allmanager->email)
            ->send(new Notification($allmanager->email,
                "Customer accepted quote for: " . $design->project->name,
                "",
                route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id,
                "View Change Request"));
        }

        Mail::to($design->project->engineer->email)
            ->send(new Notification($design->project->engineer->email,
                "Customer accepted quote for: " . $design->project->name,
                "",
                route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id,
                "View Change Request"));
        
        Mail::to(User::where('role', 'admin')->first()->email)
            ->send(new Notification(User::where('role', 'admin')->first()->email,
                "Customer accepted quote for: " . $design->project->name,
                "",
                route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id,
                "View Change Request"));

        return $cr;
    }

    public function reject(Request $request)
    {

        $this->validate($request, [
            "change_request_id" => 'required',
            "design_id" => "required"
        ]);

        $design = Auth::user()->designs()->with(['changeRequests' => function ($query) use ($request) {
            $query->findOrFail($request->change_request_id);
        }])->where('system_designs.id', $request->design_id)->firstOrFail();


        if ($design->payment_date != null) {

            $cr = $design->changeRequests[0];
            $cr->status = Statics::CHANGE_REQUEST_STATUS_REJECTED;
            $cr->save();

            $design->status_customer = Statics::DESIGN_STATUS_CUSTOMER_COMPLETED;
            $design->status_engineer = Statics::DESIGN_STATUS_ENGINEER_COMPLETED;
            $design->end_date = Carbon::now();
            $design->save();

        
    $admin = User::where('role', 'admin')->first();
    $admin->notify(new CustomerRejectQuote($design->project->name, route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id));

    User::findOrFail($design->project->customer->id)->notify(new CustomerRejectQuote($design->project->name, route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id));
        
    $managers = User::whereHas(
        'roles', function($q){
            $q->where('name', 'manager');
        }
    )->pluck('id');
    foreach($managers as $manager){
        User::findOrFail($manager)->notify(new CustomerRejectQuote($design->project->name, route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id));
    }
    $allManagers = User::whereHas(
        'roles', function($q){
            $q->where('name', 'manager');
        }
    )->get();
    foreach($allManagers as $allManager){
        Mail::to($allManager->email)
        ->send(new Notification($allManager->email,
            "Customer rejected quote for: " . $design->project->name,
            "",
            route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id,
            "View Change Request"));
    }

Mail::to($design->project->engineer->email)
    ->send(new Notification($design->project->engineer->email,
        "Customer rejected quote for: " . $design->project->name,
        "",
        route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id,
        "View Change Request"));

Mail::to(User::where('role', 'admin')->first()->email)
    ->send(new Notification(User::where('role', 'admin')->first()->email,
        "Customer rejected quote for: " . $design->project->name,
        "",
        route('proposal.view', $design->id) . "?proposal=" . $cr->proposal_id,
        "View Change Request"));

            return response()->redirectToRoute('design.view', $design->id);
        }
        return abort(402, "Design not paid for");
    }
}
