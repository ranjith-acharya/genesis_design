@extends('layouts.app')

@section('title', "Proposal for: " . $design->type->name )

@section('content')
    <div class="container-fluid">
        {{ Breadcrumbs::render('proposal', $design) }}
        <div class="card card-content container-fluid">
        <div class="row">
            <div class="valign-wrapper">
                <div class="col s11">
                    <h3 class="prussian-blue-text capitalize">{{$design->type->name}} Proposal</h3>
                    <h6>For <span class="blue-text bold">{{$design->project->name}}</span></h6>
                </div>
                <div class="col s1">
                    @if (Auth::user()->hasRole(\App\Statics\Statics::USER_TYPE_CUSTOMER))
                        <a href="{{route('design.view', $design->id)}}" class="tooltipped" data-tooltip="Go back to design"><i class="fal fa-3x fa-arrow-left blue-text"></i></a>
                    @else
                        <a href="{{route('engineer.design.view', $design->id)}}" class="tooltipped" data-tooltip="Go back to design"><i class="fal fa-3x fa-arrow-left blue-text"></i></a>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12"><br>
                <h4 class="capitalize">Design Details</h4>
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_AURORA, 'design.partials.aurora', ['design' => $design])
                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
                <br><h4>Payment</h4>
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
        <div class="row">
            <div class="col s12"><br>
                <h4 class="capitalize">Proposal</h4>
                <span class="prussian-blue-text"><b>Note</b></span>
                <blockquote>{{$design->proposals[0]->note}}</blockquote>
            </div>
        </div>
        <div class="row">
            
            <div class="col s12">
                <h4 class="capitalize">Proposal Files</h4>
                <x-ListFiles :files="$design->proposals[0]->files" path="{{route('proposal.file')}}?design={{$design->id}}&proposal={{$design->proposals[0]->id}}"></x-ListFiles>
            </div>
            
        </div>
        @if($design->proposals[0]->changeRequest)
            <div class="row">
                <div class="col s12">
                    <h4 class="capitalize">Change Request</h4>
                    <span class="prussian-blue-text"><b>Description</b></span>
                    <blockquote>{{$design->proposals[0]->changeRequest->description}}</blockquote>
                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'manager' || AUth::user()->role == 'customer')
                    <h5>Payment And Status</h5>
                    <div class="row">
                        <div class="col s6">
                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
                            <div class="mb-xxxs capitalize">
                                <span class="prussian-blue-text"><b>Quoted Price Cost: </b></span>
                                {{ ($design->proposals[0]->changeRequest->price !== null)?"$" . $design->proposals[0]->changeRequest->price: "Waiting for quote"}}
                            </div>
                            @endif
                            <div class="mb-xxxs">
                                <span class="prussian-blue-text"><b>Payment Date: </b></span>
                                {{ ($design->proposals[0]->changeRequest->payment_date)?$design->proposals[0]->changeRequest->payment_date:"Payment Pending"}}
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="mb-xxxs capitalize">
                                <span class="prussian-blue-text"><b>Status: </b></span>
                                {{ $design->proposals[0]->changeRequest->status}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="mb-xxxs capitalize">
                                <span class="prussian-blue-text"><b>Admin Note: </b></span>
                                <blockquote>{{ ($design->proposals[0]->changeRequest->engineer_note)?$design->proposals[0]->changeRequest->engineer_note:"-"}}</blockquote>
                            </div>
                        </div>
                    </div>
                    @if(sizeof($design->proposals[0]->changeRequest->files) > 0)
                        <div class="row">
                            <div class="col s12">
                                <h5 class="capitalize">Change Request Files</h5>
                                <x-ListFiles :files="$design->proposals[0]->changeRequest->files"
                                             path="{{route('change_requests.file')}}?design={{$design->id}}&changeRequest={{$design->proposals[0]->changeRequest->id}}"></x-ListFiles>
                            </div>
                        </div>
                    @endif
                @endif
                    <h5>Actions</h5>
                    <div class="center">
                        @if (Auth::user()->hasRole(\App\Statics\Statics::USER_TYPE_ENGINEER) || Auth::user()->hasRole(\App\Statics\Statics::USER_TYPE_MANAGER) || Auth::user()->hasRole(\App\Statics\Statics::USER_TYPE_ADMIN))
                            @switch($design->proposals[0]->changeRequest->status)
                                @case(\App\Statics\Statics::CHANGE_REQUEST_STATUS_REQUESTED)
                                @if(Auth::user()->role == 'admin')
                                    <a class="btn btn-large imperial-red-outline-button" id="quote_price">Quote price for the change request</a>
                                @else
                                    <span class="imperial-red white-text p-10">Waiting for Approval!</span>
                                @endif
                                @component('components.quote-change-request', ["design"=>$design])@endcomponent
                                @break
                                @case(\App\Statics\Statics::CHANGE_REQUEST_STATUS_APPROVED)
                                <a class="btn btn-large imperial-red-outline-button" href="{{route('engineer.proposal.new', $design->id)}}?changeRequest={{$design->proposals[0]->changeRequest->id}}">Send proposal for
                                    change request</a>
                                @break
                                @default
                                No actions available
                            @endswitch
                        @elseif (Auth::user()->hasRole(\App\Statics\Statics::USER_TYPE_CUSTOMER))
                            @switch($design->proposals[0]->changeRequest->status)
                                @case(\App\Statics\Statics::CHANGE_REQUEST_STATUS_AWAITING_APPROVAL)
                                <div class="row">
                                    <div class="col s12 m6 center">
                                        <form method="post" action="{{route('change_requests.reject')}}" onSubmit="return confirm('Are you sure you want to close the design?')">
                                            @csrf
                                            <input type="hidden" name="design_id" value="{{$design->id}}">
                                            <input type="hidden" name="change_request_id" value="{{$design->proposals[0]->changeRequest->id}}">
                                            <button type="submit" class="btn btn-large prussian-blue">Reject Quote and close design</button>
                                        </form>
                                    </div>
                                    <div class="col s12 m6 center">
                                        <button type="button" class="btn btn-large prussian-blue" id="accept_quote">Accept Quote of ${{$design->proposals[0]->changeRequest->price}}</button>
                                        <br><br>@component('components.accept-quote', ["design"=>$design])@endcomponent
                                        <div class="row">
                                            <div class="col s12 m4 offset-m4" id="stripe_card" style="display: none">
                                                <div class="card-panel center imperial-red honeydew-text">
                                                    <h5 id="stripe_error"></h5>
                                                    <h6>Try again later or add / change your default payment method</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="imperial-red-text">We will initiate a hold of ${{$design->proposals[0]->changeRequest->price}} when you accept this request. The funds will only be captured when send a
                                        proposal</p>
                                </div>
                                @break
                                @default
                                No actions available
                            @endswitch
                        @endif
                    </div>
                </div>
            </div>
        @elseif($design->status === \App\Statics\Statics::DESIGN_STATUS_IN_PROGRESS && Auth::user()->hasRole(\App\Statics\Statics::USER_TYPE_CUSTOMER))
            <div class="row">
                <div class="col s12 m6 center">
                    <a class="btn btn-large prussian-blue" id="start_cr"> Change request</a>
                    @component('components.change-request-form', ["design"=>$design])@endcomponent
                </div>
                <div class="col s12 m6 center">
                    <form method="post" action="{{route('design.close', $design->id)}}">
                        @csrf
                        <button type="submit" class="btn btn-large  prussian-blue" onSubmit="return confirm('Are you sure you want to close the design?')">Accept proposal and View design</button>
                    </form>
                </div>
            </div>
        @endif
        </div>
    </div>
@endsection
