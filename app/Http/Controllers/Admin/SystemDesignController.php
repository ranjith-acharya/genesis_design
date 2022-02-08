<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemDesignController extends Controller
{
    public function view($id)
    {
        //return $id;

        $design = Auth::user()->assignedDesigns()->with(['project.files.type', 'type', 'files', 'changeRequests', 'proposals' => function($query){
            $query->with(['changeRequest' => function($query){
                $query->select('id', 'proposal_id', 'status', 'created_at');
            }])->latest();
        }])->where('system_designs.id', $id)->firstOrFail();

        return view('engineer.design.view', ["design" => $design, 'fileTypes' => $design->project->files->groupBy('type.name')]);
    }
}
