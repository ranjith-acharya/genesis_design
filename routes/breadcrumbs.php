<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Support\Facades\Auth;

// Home
Breadcrumbs::for('home', function ($trail) {
    if(Auth::user()->hasRole(\App\Statics\Statics::USER_TYPE_ADMIN))
        $trail->push('Projects', route('admin.home'));
    else
        $trail->push('Projects', route('home'));
});

// designs
Breadcrumbs::for('design_list', function ($trail, $project) {
    $trail->parent('home');
    if (Auth::user()->hasRole(\App\Statics\Statics::USER_TYPE_CUSTOMER))
        $trail->push("Designs", route('design.list', $project->id));
    else
        $trail->push("Designs", route('engineer.design.list', $project->id));
});

// design
Breadcrumbs::for('design', function ($trail, $design) {
    $trail->parent('home');

    if (Auth::user()->hasRole(\App\Statics\Statics::USER_TYPE_CUSTOMER)) {
        $trail->push("Designs for: " . Str::limit($design->project->name, 15), route('design.list', $design->project->id));
        $trail->push("Design", route('design.view', $design->id));
    } else {
        $trail->push("Designs for: " . Str::limit($design->project->name, 15), route('engineer.design.list', $design->project->id));
        $trail->push("Design", route('engineer.design.view', $design->id));
    }
});

// proposal
Breadcrumbs::for('proposal', function ($trail, $design) {
    $trail->parent('home');

    if (Auth::user()->hasRole(\App\Statics\Statics::USER_TYPE_CUSTOMER)) {
        $trail->push("Designs for: " . Str::limit($design->project->name, 15), route('design.list', $design->project->id));
        $trail->push("Design type: " . $design->type->name, route('design.view', $design->id));
    } else {
        $trail->push("Designs for: " . Str::limit($design->project->name, 15), route('engineer.design.list', $design->project->id));
        $trail->push("Design type: " . $design->type->name, route('engineer.design.view', $design->id));
    }

    $trail->push("Proposal: " . $design->proposals[0]->created_at->format('F dS Y'), route('proposal.view', $design->id) . "?proposal=" . $design->proposals[0]->id);
});

Breadcrumbs::for('proposal_new', function ($trail, $design) {
    $trail->parent('home');
    $trail->push("Designs for: " . Str::limit($design->project->name, 15), route('engineer.design.list', $design->project->id));
    $trail->push("Design type: " . $design->type->name, route('engineer.design.view', $design->id));
    $trail->push("New Proposal");
});

Breadcrumbs::for('change_request_proposal_new', function ($trail, $design) {
    $trail->parent('proposal', $design);
    $trail->push("New Proposal For Change Request");
});

// project
Breadcrumbs::for('project', function ($trail, $project) {
    $trail->parent('home');
    $trail->push("Project: " . $project->name);
});

Breadcrumbs::for('project_new', function ($trail) {
    $trail->parent('home');
    $trail->push("New Project");
});

