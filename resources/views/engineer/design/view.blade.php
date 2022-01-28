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
                    @if($design->status_engineer == \App\Statics\Statics::DESIGN_STATUS_ENGINEER_HOLD || $design->status_engineer == \App\Statics\Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED || $design->status_engineer == \App\Statics\Statics::DESIGN_STATUS_ENGINEER_CHANGE_REQUEST)
                        <h6><span class="label label-red white-text bold capitalize" style="font-size:16px;">{{$design->status_engineer}}</span></h6>
                    @else
                        <h6><span class="label label-success white-text bold capitalize" style="font-size:16px;">{{$design->status_engineer}}</span></h6>
                    @endif
                </div>
                <div class="col s1 m1">
                    <a href="{{route('engineer.design.list', $design->project_id)}}" class="tooltipped" data-tooltip="Go back to design list"><i class="fal fa-3x fa-arrow-left blue-text"></i></a>
                </div>
            </div>
        </div>
    <div id="print-content">
        <div class="row"><br>
            <div class="col s12">
                <h4 class="capitalize">Project Details</h4>
                @include('components.simple-project-view', ['project' => $design->project])
            </div>
        </div><hr>
        <div class="row">
            <div class="col s12">
                <h4 class="capitalize">Design Details</h4>
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_AURORA, 'design.partials.aurora', ['design' => $design])
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_STRUCTURAL, 'design.partials.structural', ['design' => $design])
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_ELECTRICAL, 'design.partials.electrical_load', ['design' => $design])
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_PE, 'design.partials.pe_stamping', ['design' => $design])
                @includeWhen($design->type->name === \App\Statics\Statics::DESIGN_TYPE_ENGINEERING_PERMIT, 'design.partials.engineering_permit', ['design' => $design])
                @if(Auth::user()->role == 'admin')
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
            <div class="col s12 center-align">
                <button type="button" class="btn btn-small blue darken-1" onclick="printDiv('print-content')"><i class="ti-printer left"></i>Print</button>
            </div>
        </div><hr>
        <div class="row" id="messages">
            <div class="col s12">
                @if(sizeof($design->files) > 0)
                    <h4 class="capitalize">Attached Files</h4>
                    <x-ListFiles :files="$design->files" path="{{route('design.file')}}?design={{$design->id}}"></x-ListFiles>
                @endif
            </div>
        </div>
        @if ($design->status_engineer === \App\Statics\Statics::DESIGN_STATUS_ENGINEER_ASSIGNED && Auth::user()->role == 'engineer')
            <div class="row">
                <div class="col s12 center">
                    <a class="btn btn-large indigo imperial-red-outline-button" href="{{route('engineer.design.start', $design->id)}}">Start&nbsp;Work&nbsp;On&nbsp;Design</a>
                </div>
            </div>
        @endif
        @if(Auth::user()->role == 'admin' || Auth::user()->role =='manager')
        <div class="row" id="messages">
            <div class="col s12"><br>
                <h4>Messages</h4>
                @if ($design->status_engineer === \App\Statics\Statics::DESIGN_STATUS_ENGINEER_ASSIGNED)
                    <p class="imperial-red-text center">Start work on this design to enable messaging</p>
                @else
                    <x-DesignMessages :designID="$design->id" readOnly="{{$design->status_engineer === \App\Statics\Statics::DESIGN_STATUS_ENGINEER_COMPLETED}}"></x-DesignMessages>
                @endif
            </div>
        </div>
        @endif
        <hr><div class="row" id="proposals">
            <div class="col s12">
                <h4>Proposals</h4>
                @if ($design->status_engineer === \App\Statics\Statics::DESIGN_STATUS_ENGINEER_ASSIGNED)
                    <p class="imperial-red-text center">Start work on this design to submit a proposal</p>
                @elseif(sizeof($design->proposals) === 0 && $design->status_engineer === \App\Statics\Statics::DESIGN_STATUS_ENGINEER_PROGRESS)
                    <div class="center"><a class="btn btn-large prussian-blue" href="{{route('engineer.proposal.new', $design->id)}}">Submit</a></div>
                @elseif(sizeof($design->proposals) > 0)
                    <div class="row">
                        <div class="col s4 left-align prussian-blue-text bold center">Note</div>
                        <div class="col s2 left-align black-text center">Change request status</div>
                        <div class="col s4 left-align black-text center">Created At</div>
                        <div class="col s2 left-align black-text center"></div>
                    </div>
                    <ul class="collection">
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
        </div><hr>
        @if($design->status_engineer == \App\Statics\Statics::DESIGN_STATUS_ENGINEER_HOLD || $design->status_engineer == \App\Statics\Statics::DESIGN_STATUS_ENGINEER_PROGRESS || $design->status_engineer == \App\Statics\Statics::DESIGN_STATUS_ENGINEER_ASSIGNED || $design->status_engineer == \App\Statics\Statics::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED)
        <div class="row">
            <div class="col s12">
                <h4>Set Status</h4>
                <div class="row">
                    @csrf<div class="input-field col s4">
                        <select id="statusOption" name="statusOption">
                            <option value="" disabled selected>Set Status</option>
                            <option value="{{ \App\Statics\Statics::DESIGN_STATUS_ENGINEER_HOLD }}">{{ \App\Statics\Statics::DESIGN_STATUS_ENGINEER_HOLD }}</option>
                            <option value="{{ \App\Statics\Statics::DESIGN_STATUS_ENGINEER_PROGRESS }}">{{ \App\Statics\Statics::DESIGN_STATUS_ENGINEER_PROGRESS }}</option>
                        </select>
                    </div>
                    <div class="input-field col s4" id="holdStatusOption">
                        <input id="holdStatusNote" name="holdStatusNote" type="text" class="required" placeholder=" ">
                        <label for="holdStatusNote">Note : </label>
                    </div>
                    <div class="input field col s4">
                        <br>
                        <button type="submit" class="btn btn-small green"onclick="setStatus();">Update Status</button>
                    </div>
                </div>
            </div>
            <div class="col s12">
                @if($design->status_engineer == \App\Statics\Statics::DESIGN_STATUS_ENGINEER_HOLD)
                <hr>
                <div class="row">
                    <div class="col s12">
                        <h4>Project Update</h4>
                        @if($design->note == "")

                        @else
                        <div class="center">
                            <b class="label label-red white-text p-10" style="font-size:15px;">{{ $design->note }}</b>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div><hr>
        @endif
        @if ($design->status_engineer === \App\Statics\Statics::DESIGN_STATUS_ENGINEER_ASSIGNED && Auth::user()->role == 'engineer')
            <div class="row">
                <div class="col s12 center">
                    <a class="btn btn-large indigo imperial-red-outline-button" href="{{route('engineer.design.start', $design->id)}}">Start&nbsp;Work&nbsp;On&nbsp;Design</a>
                </div>
            </div>
        @endif
        </div>
    </div>
    </div>
@endsection

@section('js')
<script>
    function setStatus(a){
        //alert(a.value);
        var status = document.getElementById("statusOption").value;
        var id = {{ $design->id }};
        var _token=$('input[name="_token"]').val();
        var otherInput = document.getElementById("holdStatusOption");
        var note  = document.getElementById("holdStatusNote").value;
        // alert(note);
        // alert(_token);
        // alert(id);
        // alert(status);
        $.ajax({
            url:"@if(Auth::user()->role == 'admin'){{ route('admin.projects.set.status') }}@elseif(Auth::user()->role == 'manager'){{ route('manager.projects.set.status') }}@else{{ route('engineer.project.set.status') }}@endif",
            method:"POST",
            data:{statusName: status, designId: id, statusNote: note, _token:_token},
            success:function(data){
                console.log(data);
                toastr.success('Status Set Successfully!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                location.reload();
            }      
        });
    }
    </script>
    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
    }
    </script>
@endsection