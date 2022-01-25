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
        url: "{{ route('admin.projects.getProjects') }}" + "?page=" + page,
        success:function(data) {
            console.log(data);
          $('#projectData').html(data);
        }
      });
    }
</script>
<script>

$(document).ready(function() {
        $(document).on('click', '.pagination a', function(event) {
          event.preventDefault();
          var page = $(this).attr('href').split('page=')[1];
         
         getMoreUsers(page);
        });

       

       
    });
    function setProjectID(name,id,design_id)
    {
        
        $('#project_id').val(id);
        $("#design_id").val(design_id);
        $("#assign_form").attr('action',"@if(Auth::user()->role == 'admin'){{ route('admin.assign') }}@else{{ route('manager.assign') }}@endif");
        $("#project_name").text(name);
        //alert(id)
       var modelid=id;
        $.ajax({
                url:"@if(Auth::user()->role == 'admin'){{url('admin/projects')}}@else{{url('manager/projects')}}@endif"+"/"+id+"/assign",
                method:"POST",
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
    
</script>
@endsection
@section('content')
<div class="container-fluid black-text">
    <div class="row">
        <div class="col s12">
            <div class="card">
                @if ($message = Session::get('success'))
                <script>
                    toastr.success('{{$message}}', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                </script>
	            @endif
                <div>
                    <div class="row">
                        <div class="col s3">
                            <h3>List of Projects</h3>
                        </div>
</div>
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
               
            </div>
           
       
                    <div id="projectData">
                        @include('admin.reports.projects')
                        <div>
        <style>
            .pager{
                display: inline-flex !important;
            }
            .pager > li{
                padding-inline: 10px;
            }
        </style>
        {{ $projects->links('vendor.pagination.custom') }}
    </div>
                    </div>
                    <div id="assignModel" class="modal modal-fixed-footer">
                        <div class="modal-content">
                            <h4>Select Engineer to Assign <span id="project_name"></span> project</h4>
                            <form method="post" id="assign_form">
                            @csrf
                                <div class="input-field col s12">
                                    <select class="browser-default" name="engineer_id" id="engineer_select">
                                        <option disabled >Select Engineer</option>
                                        @foreach($engineers as $engineer)
                                            <option value="{{ $engineer->id }}">{{ $engineer->first_name }} {{ $engineer->last_name }}</option>
                                        @endforeach
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
</div>
@endsection