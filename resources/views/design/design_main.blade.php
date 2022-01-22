@extends('layouts.app')

@section('title', $design->type->name . " for: " .  $design->project->name)

@section('content')
    <div class="container-fluid">
        {{ Breadcrumbs::render('design', $design) }}
        <div class="card card-content container-fluid">
        <div class="row">
            <div class="valign-wrapper">
                <div class="col s11 m9">
                    <h3 class="capitalize">{{$design->type->name}}</h3>
                    <h6>For <span class="blue-text bold ">{{$design->project->name}}</span></h6>
                </div>
                <div class="col s12 m2 hide-on-small-and-down">
                    <h3 class="capitalize">Status</h3>
                    @if($design->status_customer == \App\Statics\Statics::DESIGN_STATUS_CUSTOMER_HOLD || $design->status_customer == \App\Statics\Statics::DESIGN_STATUS_CUSTOMER_REQUESTED || $design->status_customer == \App\Statics\Statics::DESIGN_STATUS_CUSTOMER_CHANGE_REQUEST)
                        <h6><span class="label label-red white-text bold capitalize" style="font-size:16px;">{{$design->status_customer}}</span></h6>
                    @else
                        <h6><span class="label label-success white-text bold capitalize" style="font-size:16px;">{{$design->status_customer}}</span></h6>
                    @endif
                </div>
                <div class="col s1 m1">
                    <a href="{{route('design.list', $design->project_id)}}" class="tooltipped" data-tooltip="Go back to design list"><i class="fal fa-3x fa-arrow-left blue-text"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12"><br>   
                <h4 class="capitalize">Design Details</h4>
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_AURORA, 'design.partials.aurora', ['design' => $design])
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_STRUCTURAL, 'design.partials.structural', ['design' => $design])
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_ELECTRICAL, 'design.partials.electrical_load', ['design' => $design])
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_PE, 'design.partials.pe_stamping', ['design' => $design])
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_ENGINEERING_PERMIT, 'design.partials.engineering_permit', ['design' => $design])
                <hr><h4 class="capitalize">Payment</h4>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Design Cost: </b></span>
                    ${{ $design->price}}
                </div>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Payment Date: </b></span>
                    {{ ($design->payment_date)?$design->payment_date:"Payment Pending"}}
                </div><hr>
                @if(sizeof($design->files) > 0)
                    <h4 class="capitalize">Attached Files</h4>
                    <x-ListFiles :files="$design->files" path="{{route('design.file')}}?design={{$design->id}}"></x-ListFiles>
                @endif
            </div>
        </div><hr>
        <div class="row" id="messages">
            <div class="col s12">
                <h4>Messages</h4>
                <x-DesignMessages :designID="$design->id" readOnly="{{$design->status_customer === \App\Statics\Statics::DESIGN_STATUS_COMPLETED}}"></x-DesignMessages>
            </div>
        </div><hr>
        <div class="row" id="proposals">
            <div class="col s12">
                <h4>Proposals</h4>
                <div class="row">
                    <div class="col s4 left-align prussian-blue-text bold center">Note</div>
                    <div class="col s2 left-align black-text center">Has Change Request?</div>
                    <div class="col s4 left-align black-text center">Created At</div>
                    <div class="col s2 left-align center"></div>
                </div>
                <ul class="collection">
                    @if (sizeof($design->proposals) > 0)
                        @foreach($design->proposals as $proposal)
                            <li class="collection-item mb-xxs">
                                <div class="row">
                                    <div class="col s4 center">
                                        {{Str::limit($proposal->note,45)}}
                                    </div>
                                    <div class="col s2 center">{{ ($proposal->change_request_count)?'Yes':'No'}}</div>
                                    <div class="col s4 center">{{$proposal->created_at->format('F dS Y - h:i A')}} (UTC)</div>
                                    <div class="col s2 center"><a class="steel-blue-text" href="{{route('proposal.view', $design->id)}}?proposal={{$proposal->id}}">View</a></div>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="collection-item mb-xxs center">
                            <div class="center center-align">
                                <span>No proposals generated yet</span>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        </div>
    </div>
@endsection
