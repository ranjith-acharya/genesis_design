@extends('layouts.app')

@section('title')
Project Index - Genesis Design
@endsection
@push('style')
	<style type="text/css">
        ul{
            white-space:nowrap !important;
        }
		.my-active span{
			background-color: #5cb85c !important;
			color: white !important;
			border-color: #5cb85c !important;
		}
	</style>
@endpush
@section('js')
<script>
function filter() {
            getMoreUsers(1);
        }
        $('#project_search').on('keyup', function() {
         
          getMoreUsers(1);
        });
function getMoreUsers(page) {
    M.FormSelect.init(document.querySelectorAll('select'));
    var filters = [
                {field: "project_type_id", value: M.FormSelect.getInstance(document.getElementById('project_type_select')).getSelectedValues()[0]},
                {field: "project_status", value: M.FormSelect.getInstance(document.getElementById('project_status_select')).getSelectedValues()[0]},
                {field: "status", value: M.FormSelect.getInstance(document.getElementById('status_select')).getSelectedValues()[0]}
            ]
    var search=$('#project_search').val();
    //console.log(search+" "+project_type_id+" "+project_status+" "+status);
       $.ajax({
        type: "GET",
        data: {
          'search':search,
          'filters': JSON.stringify(filters)
        },
        url: "{{ route('project.getProjects') }}" + "?page=" + page,
        success:function(data) {
            console.log(data);
          $('#user_data').html(data);
        }
      });
    }
        </script>
@endsection
@section('content')

<div class="container-fluid black-text">

        @if (!Auth::user()->email_verified_at)
            <div class="row">
                <div class="col s12">
                    <div class="card-panel imperial-red center white-text">
                        Please verify you email address to enable full functionality. If you did not receive an email from us <a href="{{route('verification.notice')}}">click here</a> to resend the email.
                    </div>
                </div>
            </div>
        @else
    <div class="row">
        <div class="col s12">
            <div class="">
                @if ($message = Session::get('success'))
                <script>
                    toastr.success('{{$message}}', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                </script>
	            @endif

            <div class="row">
                <div class="col s6">
                    <h3>Your Projects</h3>
                </div>
                <div class="col s6 right-align">
                    @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER||Auth::user()->role === \App\Statics\Statics::USER_TYPE_ADMIN)
                        <a href="{{ route('project.bulk') }}"><button class="btn prussian-blue">Multiple Project</button></a>
                        <a class="btn prussian-blue dropdown-trigger" data-target='dropdown1'>   Single Project</a>
                        <!-- Dropdown Structure -->
                        <ul id='dropdown1' class='dropdown-content prussian-blue-text'>
                            @foreach($projectTypes as $projectType)
                                <li><a href="{{route('project.form', Str::slug($projectType->name))}}">{{Str::ucfirst($projectType->name)}}</a></li>
                                <li class="divider" tabindex="-1"></li>
                            @endforeach
                        </ul>
                    @else
                        <!-- <a class="btn imperial-red-outline-button" href="{{route('engineer.project.available')}}">Start&nbsp;a&nbsp;project</a> -->
                    @endif
                </div>
            </div><br><br>
            <div class="row mb-0">
                <div class="col s12 m9 center-on-small-only">
                    <div class="col s3">
                        <div class="input-field inline">
                            <input id="project_search" type="text" data-type="projects">
                            <label for="project_search">Search for project(s)...</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field inline">
                        <select id="project_type_select" onchange="filter()">
                                <option value="all">All</option>
                                @foreach($projectTypes as $projectType)
                                    <option value="{{$projectType->id}}">{{Str::ucfirst($projectType->name)}}</option>
                                @endforeach
                            </select>
                            <label for="project_type_select">Project Type</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field inline">
                            <select id="status_select" onchange="filter()">
                                <option value="all">All</option>
                                    @foreach(\App\Statics\Statics::STATUSES as $Status)
                                        <option value="{{$Status}}">{{Str::ucfirst($Status)}}</option>
                                    @endforeach
                            </select>
                            <label for="status_select"> State</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field inline">
                            <select id="project_status_select" onchange="filter()">
                                <option value="all">All</option>
                                    @foreach(\App\Statics\Statics::DESIGN_STATUS_CUSTOMER as $projectStatus)
                                        <option value="{{$projectStatus}}">{{Str::ucfirst($projectStatus)}}</option>
                                    @endforeach
                            </select>  
                            <label for="project_status_select">Project Status</label>
                        </div>
                    </div>
                </div>
                <!-- <div class="col s12 m3 center-on-small-only right-on-lg-and-up" style="padding-top: 20px">
                    <a class="btn-flat tooltipped waves-effect" data-position="top" data-tooltip="Previous Page" onclick="prevPage()">
                        <i class="fal fa-angle-left"></i>
                    </a>
                    Page <span id="current_page">1</span> of <span id="last_page">1</span>
                    <button class="btn-flat tooltipped waves-effect" data-position="top" data-tooltip="Next Page" onclick="nextPage()">
                        <i class="fal fa-angle-right"></i>
                    </button>
                </div> -->
            </div>
           
        </div>
                <div id="user_data">
    @include('pages.project')
  </div>
  @endif

                    <div id="assignModel" class="modal modal-fixed-footer">
                        <div class="modal-content">
                            <h4>Select Engineer to Assign <span id="project_name"></span> project</h4>
                            <form method="post" id="assign_form">
                            @csrf
                                <div class="input-field col s12">
                                    <select class="browser-default" name="engineer_id" id="engineer_select">
                                        <option disabled >Select Engineer</option>
                                        
                                    </select>
                                </div>
                                <div class="col s12">
                                    <input type="hidden" name="project_id" id="project_id" value="">
                                    <input type="hidden" name="design_id" id="design_id" value="">
                                    <a><button type="submit" class="green white-text btn btn-small">Assign</button></a>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                        <a href="#!" class="modal-close btn-flat imperial-red-text" type="reset">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $(document).on('click', '.pagination a', function(event) {
          event.preventDefault();
          var page = $(this).attr('href').split('page=')[1];
         
         getMoreUsers(page);
        });

       

       
    });

</script>
<script>
    function setProjectID(name,id,design_id)
    {
        console.log(design_id);
        $('#project_id').val(id);
        $("#design_id").val(design_id);
        $("#assign_form").attr('action',"@if(Auth::user()->role == 'admin'){{ route('admin.assign') }}@else{{ route('manager.assign') }}@endif");
        $("#project_name").text(name);
        //alert(id)
       var modelid=id;
        $.ajax({
                url:"@if(Auth::user()->role == 'admin'){{url('admin/projects')}}@else{{url('manager/projects')}}@endif"+"/"+id+"/assign",
                method:"GET",
                datatype:"JSON",
                success:function(data)
                {
                   //alert(data);
                   console.log(data);
                   //$("#engineer_select").val(data['engineer_id']).attr("selected", "selected");
                   $('#engineer_select option[value="'+data['engineer_id']+'"]').attr("selected", "selected");
                    // $('#updateForm').attr('action',"{{url('fuel_details')}}"+"/"+id); 
                    // $("#method").val("PATCH");        
                }
            });
    }
    function archiveProject(id){
        $("#archiveForm"+id).submit();
    }

    var checkID = [];

    // $(".checkboxAll").click(function(){
    //     // alert("Hey!");
    //     if(this.checked){
           
    //         checkID.push(this.value);
    //     }
    //     else{
    //         checkID.splice(checkID.indexOf(this.value),1);
    //     }
    //     if(checkID.length>0)
    //     {
    //         $("#archive").css("display","block");
    //         $("#assign").css("display","block");
    //     }
    //     else{
    //         $("#archive").css("display","none");
    //         $("#assign").css("display","none");
    //     }
    // });
    // $("#selectAll").click(function(){
    //     checkID = [];
    //     if(this.checked){
    //         // alert("checked");
    //         $(".checkboxAll").each(function(id, checkboxValue) {
    //             $(".checkboxAll").prop("checked", true);
    //             //console.log(checkboxValue.value);               
    //             checkID.push(checkboxValue.value);
    //         });
    //         console.log(checkID);
    //     }else{
    //         $(".checkboxAll").each(function(id, checkboxValue) {
    //             $(".checkboxAll").prop("checked", false);
    //             checkID.splice(checkID.indexOf(checkboxValue.value),1);
    //         });
    //     }
    //     if(checkID.length>0)
    //     {
    //         $("#archive").css("display","block");
    //         $("#assign").css("display","block");
    //     }
    //     else{
    //         $("#archive").css("display","none");
    //         $("#assign").css("display","none");
    //     }
    // });
    
</script>
@endsection