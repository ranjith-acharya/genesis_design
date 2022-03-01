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
      $(document).ready(function() {
        $(document).on('click', '.pager a', function(event) {
          event.preventDefault();
          var page = $(this).attr('href').split('page=')[1];
        
         getMoreUsers(page);
      
        });
    });
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
                                <li><a href="{{route('project.form', Str::slug($projectType->name))}}">{{ucwords(strtolower($projectType->name))}}</a></li>
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
                            <label for="project_search">Search For Project(s)...</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field inline">
                        <select id="project_type_select" onchange="filter()">
                                <option value="all">All</option>
                                @foreach($projectTypes as $projectType)
                                    <option value="{{$projectType->id}}">{{ucwords(strtolower($projectType->name))}}</option>
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
                                        <option value="{{$Status}}">{{ucwords(strtolower($Status))}}</option>
                                    @endforeach
                            </select>
                            <label for="status_select"> State</label>
                        </div>
                    </div>
                    @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER)
                    <div class="col s3">
                        <div class="input-field inline">
                            <select id="project_status_select" onchange="filter()">
                                <option value="all">All</option>
                                    @foreach(\App\Statics\Statics::DESIGN_STATUS_CUSTOMER as $projectStatus)
                                        <option value="{{$projectStatus}}">{{ucwords(strtolower($projectStatus))}}</option>
                                    @endforeach
                            </select>  
                            <label for="project_status_select">Project Status</label>
                        </div>
                    </div>
                    @else
                    <div class="col s3">
                        <div class="input-field inline">
                            <select id="project_status_select" onchange="filter()">
                                <option value="all">All</option>
                                    @foreach(\App\Statics\Statics::DESIGN_STATUS_ENGINEER as $projectStatus)
                                        <option value="{{$projectStatus}}">{{ucwords(strtolower($projectStatus))}}</option>
                                    @endforeach
                            </select>  
                            <label for="project_status_select">Project Status</label>
                        </div>
                    </div>
                    @endif
                </div>
                
            </div>
           
        </div>
                <div id="user_data">
    @include('pages.project')
  </div>
  @endif

                    
                </div>
            </div>
        </div>
    
</div>
@endsection

