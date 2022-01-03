@extends('layouts.app')

@section('title', $design->type->name . " for: " .  $design->project->name)

@section('content')
    <div class="container-fluid">
        {{ Breadcrumbs::render('design', $design) }}
        <div class="row">
            <div class="valign-wrapper">
                <div class="col s11 m9">
                    <h3 class="capitalize">{{$design->type->name}}</h3>
                    <h6>For <span class="imperial-red-text bold ">{{$design->project->name}}</span></h6>
                </div>
                <div class="col s12 m2 hide-on-small-and-down">
                    <h3 class="capitalize">Status</h3>
                    <h6><span class="imperial-red-text bold capitalize">{{$design->status}}</span></h6>
                </div>
                <div class="col s1 m1">
                    <a href="{{route('engineer.design.list', $design->project_id)}}" class="tooltipped" data-tooltip="Go back to design list"><i class="fal fa-3x fa-arrow-left steel-blue-text"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <h4 class="capitalize">Project Details</h4>
                @include('components.simple-project-view', ['project' => $design->project])
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <h4 class="capitalize">Design Details</h4>
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_AURORA, 'design.partials.aurora', ['design' => $design])
                @if(Auth::user()->role == 'admin')
                <h5>Payment</h5>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Design Cost: </b></span>
                    ${{ $design->price}}
                </div>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Payment Date: </b></span>
                    {{ ($design->payment_date)?$design->payment_date:"Payment Pending"}}
                </div>
                @endif
            </div>
        </div>
        <div class="row" id="messages">
            <div class="col s12">
                @if(sizeof($design->files) > 0)
                    <h4 class="capitalize">Attached Files</h4>
                    <x-ListFiles :files="$design->files" path="{{route('design.file')}}?design={{$design->id}}"></x-ListFiles>
                @endif
            </div>
        </div>
        @if ($design->status === \App\Statics\Statics::DESIGN_STATUS_REQUESTED)
            <div class="row">
                <div class="col s12 center">
                    <a class="btn btn-large imperial-red-outline-button" href="{{route('engineer.design.start', $design->id)}}">Start&nbsp;Work&nbsp;On&nbsp;Design</a>
                </div>
            </div>
        @endif
        <div class="row" id="messages">
            <div class="col s12">
                <h4>Messages</h4>
                @if ($design->status === \App\Statics\Statics::DESIGN_STATUS_REQUESTED)
                    <p class="imperial-red-text center">Start work on this design to enable messaging</p>
                @else
                    <x-DesignMessages :designID="$design->id" readOnly="{{$design->status === \App\Statics\Statics::DESIGN_STATUS_COMPLETED}}"></x-DesignMessages>
                @endif
            </div>
        </div>
        <div class="row" id="proposals">
            <div class="col s12">
                <h4>Proposals</h4>
                @if ($design->status === \App\Statics\Statics::DESIGN_STATUS_REQUESTED)
                    <p class="imperial-red-text center">Start work on this design to submit a proposal</p>
                @elseif(sizeof($design->proposals) === 0 && $design->status === \App\Statics\Statics::DESIGN_STATUS_IN_PROGRESS)
                    <div class="center"><a class="btn btn-large imperial-red-outline-button" href="{{route('engineer.proposal.new', $design->id)}}">Submit&nbsp;a&nbsp;proposal</a></div>
                @elseif(sizeof($design->proposals) > 0)
                    <div class="row">
                        <div class="col s4 left-align imperial-red-text bold center">Note</div>
                        <div class="col s2 left-align center">Change request status</div>
                        <div class="col s4 left-align center">Created At</div>
                        <div class="col s2 left-align center"></div>
                    </div>
                    <ul class="collection" style="border: none">
                        @foreach($design->proposals as $proposal)
                            <li class="collection-item mb-xxs">
                                <div class="row">
                                    <div class="col s4 center">
                                        {{Str::limit($proposal->note,45)}}
                                    </div>
                                    <div class="col s2 center capitalize">{{($proposal->changeRequest)?$proposal->changeRequest->status:"-"}}</div>
                                    <div class="col s4 center">{{$proposal->created_at->format('F dS Y - h:i A')}} (UTC)</div>
                                    <div class="col s2 center"><a class="steel-blue-text" href="{{route('proposal.view', $design->id)}}?proposal={{$proposal->id}}">View</a></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        @if ($design->status === \App\Statics\Statics::DESIGN_STATUS_REQUESTED)
            <div class="row">
                <div class="col s12 center">
                    <a class="btn btn-large imperial-red-outline-button" href="{{route('engineer.design.start', $design->id)}}">Start&nbsp;Work&nbsp;On&nbsp;Design</a>
                </div>
            </div>
        @endif
    </div>
@endsection
