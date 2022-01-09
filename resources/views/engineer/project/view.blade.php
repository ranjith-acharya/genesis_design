@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <div class="container-fluid">
        {{ Breadcrumbs::render('project', $project) }}
        <div class="card card-content container-fluid">
        <div class="row">
            <div class="col s6">
                <h4><span class="prussian-blue-text bold">{{$project->name}}</span></h4>
                <h5 class="capitalize">{{$projectType->name}}</h5>
            </div>
            <div class="col s6 right-align">
                <a class="btn prussian-blue m-xxxs" href="{{route('engineer.design.list', $project->id)}}">View Designs</a>
            </div>
        </div>
            </br>
        <div class="row">
            <div class="col s12">
                <h4 class="capitalize">Project Details</h4>
                @include('components.simple-project-view', ['project' => $project])
            </div>
        </div>
        <div class="row center">
            <a class="btn prussian-blue m-xxxs" href="{{route('engineer.design.list', $project->id)}}">View Designs</a>
        </div>
        </div>
    </div>
@endsection
