<?php

namespace App\Http\Controllers;

use App\Mail\Notification;
use App\Notifications\MessageNotify;
use App\Statics\Statics;
use App\SystemDesign;
use App\SystemDesignMessage;
use App\SystemDesignMessageFile;
use App\SystemDesignPrice;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function insert(Request $request)
    {
        $this->validate($request, [
            "message" => 'required',
            "design" => "required"
        ]);

        $design = SystemDesign::with(['project.customer', 'project.engineer', 'type'])->findOrFail($request->design);

        $message = new SystemDesignMessage();
        $message->message = $request->message;
        $message->sender_id = Auth::id();
        $message->system_design_id = $request->design;
        $message->save();

        // Mail::to($design->project->customer->email)
        //         ->send(new Notification($design->project->customer->email,
        //             "New message for: " . $design->type->name . " in project: " . $design->project->name,
        //             Str::limit($request->message, 45),
        //             route('design.view', $design->id), "View Message"));

//        If end, send email to customer
        if (Auth::user()->role == Statics::USER_TYPE_ADMIN || Auth::user()->role == Statics::USER_TYPE_MANAGER) {
            
            User::findOrFail($design->project->customer->id)->notify(new MessageNotify($design->project->name, route('design.view', $design->id)));
            Mail::to($design->project->customer->email)
                ->send(new Notification($design->project->customer->email,
                    "New message for: " . $design->type->name . " in project: " . $design->project->name,
                    Str::limit($request->message, 45),
                    route('design.view', $design->id), "View Message"));
        } else {
            $admin = User::where('role', 'admin')->first();
            //return $admin;
            $admin->notify(new MessageNotify($design->project->name, route('engineer.design.view', $design->id)));
            Mail::to(User::where('role', 'admin')->first()->email)
                ->send(new Notification(User::where('role', 'admin')->first()->email,
                    "New message for: " . $design->type->name . " in project: " . $design->project->name,
                    Str::limit($request->message, 45),
                    route('engineer.design.view', $design->id),
                    "View Message"));

            $managers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->pluck('id');
            foreach($managers as $manager){
                User::findOrFail($manager)->notify(new MessageNotify($design->project->name, route('engineer.design.view', $design->id)));
            }
    
            $allmanagers = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'manager');
                }
            )->get();
            foreach($allmanagers as $allmanager){
                Mail::to($allmanager->email)
                ->send(new Notification($allmanager->email,
                    "New message for: " . $design->type->name . " in project: " . $design->project->name,
                    Str::limit($request->message, 45),
                    route('engineer.design.view', $design->id),
                    "View Message"));
            }
        }

        return $message;

    }

    public function attachFile(Request $request)
    {
        $this->validate($request, [
            'path' => 'required|string|max:255',
            'system_design_message_id' => 'required|numeric',
            'content_type' => 'required|string'
        ]);
        return SystemDesignMessageFile::create($request->all());
    }

    public function getFile(Request $request)
    {

        $this->validate($request, [
            'message' => 'required|string|max:255',
            'file' => 'required|string|max:255',
            'design' => 'required|string|max:255'
        ]);
        $engineer = SystemDesign::findOrFail($request->design)->project->engineer;
        // return $engineer;
        $design = $engineer->designs()->with('messages.files')->where('system_designs.id', $request->design)->firstOrFail();

        $message = null;
        foreach ($design->messages as $msg)
            if ($msg->id == $request->message)
                $message = $msg;

        if ($message) {

            $file = null;
            foreach ($message->files as $f)
                if ($f->id == $request->file)
                    $file = $f;

            if ($file) {
                $url = Http::get(env('SUN_STORAGE') . "/file/url?ttl_seconds=900&api-key=" . env('SUN_STORAGE_KEY') . "&file_path=" . $file->path)->body();
                return redirect()->away(json_decode($url));
            }
        }

        abort(404);
        return null;
    }

    public function unread()
    {
        $query = null;
        if (Auth::user()->role === Statics::USER_TYPE_CUSTOMER)
            $query = Auth::user()->projects();
        else
            $query = Auth::user()->assignedProjects();

        $projects = $query->with(['designs' => function ($query) {
            $query->with(["type" => function ($query) {
                $query->select('system_design_types.id', 'system_design_types.name');
            }, 'messages' => function ($query) {
                $query->where('read', false)->where('sender_id', '<>', Auth::id())->select('system_design_messages.id', 'system_design_messages.system_design_id', 'system_design_messages.created_at');
            }])->where('project_status', '<>', Statics::DESIGN_STATUS_COMPLETED)->select('system_designs.id', 'system_designs.project_id', 'system_designs.system_design_type_id');
        }])->where('project_status', '<>', Statics::PROJECT_STATUS_ARCHIVED)->get(['name', 'id']);


        $notifications = new Collection();
        foreach ($projects as $project)
            foreach ($project->designs as $design)
                foreach ($design->messages as $message)
                    $notifications->push([
                        "time" => $message->created_at->format('F dS Y - h:i A'),
                        "design" => $design->id,
                        "design_type" => $design->type->name,
                        "project" => $project->name,
                    ]);

        return $notifications;
    }

    public function markRead($design)
    {
        if (Auth::user()->hasRole(Statics::USER_TYPE_CUSTOMER))
            $query = Auth::user()->designs();
        else
            $query = Auth::user()->assignedDesigns();

        $d = $query->findORFail($design);
        if ($d)
            $messages = SystemDesignMessage::where('system_design_id', $design)->where('sender_id', "<>", Auth::id())->update(["read" => true]);

        return ["done"];
    }
}
