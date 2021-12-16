@extends('layouts.app')

@section('title', 'Available Projects')

@section('content')
    <div class="container">
        @if (!Auth::user()->email_verified_at)
            <div class="row">
                <div class="col s12">
                    <div class="card-panel imperial-red center white-text">
                        Please verify you email address to enable full functionality. If you did not receive an email from us <a href="{{route('verification.notice')}}">click here</a> to resend the email.
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col s12 m9">
                <h3>Available Projects</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 mt-0 mb-0">
                <div class="card-panel z-depth-0 transparent pb-xxs mb-0 mt-0" style="flex-direction: column; padding: 1rem">
                    <div class="row mb-0 w100">
                        <div class="col s12 center">
                            <div class="col s4 m3 left-align imperial-red-text bold center">Name</div>
                            <div class="col s3 m2 left-align center">City</div>
                            <div class="col s3 m2 left-align center">State (Region)</div>
                            <div class="col s3 m2 left-align center">Project Type</div>
                            <div class="col m3 left-align center"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <ul class="collection" id="pagination_target" style="border: none">
                    @if(sizeof($projects) > 0)
                    @foreach($projects as $project)
                        <li class="collection-item mb-xxs">
                            <div class="row mb-0 w100">
                                <div class="col s12 center">
                                    <div class="valign-wrapper">
                                        <div class="col s4 m3 left-align imperial-red-text bold center">{{ $project->name }}</div>
                                        <div class="col s3 m2 left-align center capitalize">{{ $project->city }}</div>
                                        <div class="col s3 m2 left-align center capitalize">{{ $project->state }}</div>
                                        <div class="col s3 m2 left-align center capitalize">{{ $project->type->name }}</div>
                                        <div class="col s12 m3 right-align hide-on-med-and-down center">
                                            <a class="btn steel-blue-outline-button ml-xxxs" href="{{route('engineer.project.assign', $project->id)}}">Assign to self</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide-on-med-and-up mt-s">
                                <div class="col s12 center">
                                    <a class="btn steel-blue-outline-button ml-xxxs" href="{{route('engineer.project.assign', $project->id)}}">Assign to self</a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                        @else
                        <li class="collection-item mb-xxs">
                            <div class="row mb-0 w100">
                                <div class="col s12 center">
                                    No projects available to work on yet
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection
