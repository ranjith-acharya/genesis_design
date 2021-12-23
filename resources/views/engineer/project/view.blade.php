@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <div class="container-fluid">
        {{ Breadcrumbs::render('project', $project) }}
        <div class="row">
            <div class="col s12 m10">
                <h4><span class="imperial-red-text">{{$project->name}}</span></h4>
                <h5 class="capitalize">{{$projectType->name}}</h5>
            </div>
        </div>
        <div class="center">
            <a class="btn imperial-red-outline-button m-xxxs" href="{{route('engineer.design.list', $project->id)}}">View Designs</a>
        </div>
        <div class="row">
            <div class="col s12">
                <h4 class="capitalize">Project Details</h4>
                @include('components.simple-project-view', ['project' => $project])
            </div>
        </div>
        <div class="row center">
            <a class="btn imperial-red-outline-button m-xxxs" href="{{route('engineer.design.list', $project->id)}}">View Designs</a>
        </div>
    </div>
@endsection
