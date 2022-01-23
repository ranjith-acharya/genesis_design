@extends('layouts.app')

@section('title')
Project Index - Genesis Design
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
                <div class="card-content">
                    <div class="row">
                        <div class="col s3">
                            <h3>List of Projects</h3>
                        </div>
                        <div class="col s3 ">
                            <p><label>
                                <input type="button" class="btn btn-primary" style="display:none;" id="archive" onclick="archiveAll()" value="Archive All"/>
                            </label></p>
</div>
<div class="col s3 ">
                            <p><label>
                                <input type="button" class="btn btn-primary" style="display:none;" id="assign" onclick="assignAll()" value="Assign All"/>
                            </label></p>
</div>
                        <!-- <div class="col s3 right-align">
                            <p><label>
                                <input type="checkbox" class="filled-in" id="selectAll"/><span>Select All</span>
                            </label></p> -->
                    
                    <!-- <button class="btn indigo dropdown-trigger" data-target='dropdown1'><i class="material-icons left">add</i>NEW PROJECT</button>
                        <ul id='dropdown1' class='dropdown-content'>
                            <li><a href="http://127.0.0.1:8000/project/new/residential">Residential</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="http://127.0.0.1:8000/project/new/commercial">Commercial</a></li>
                            <li class="divider" tabindex="-1"></li>
                        </ul> -->                   
                        <!-- </div> -->
                    </div>
                    <table id="zero_config" class="responsive-table display">
                        <thead>
                            <tr class="black-text">
                                <!-- <th>Select</th> -->
                                <th>Project Name</th>
                                <th>Service Name</th>
                                <th>Assigned To</th>
                                <th>Assigned Date</th>
                                <!-- <th>Design Type</th> -->
                                <th>State</th>
                                <th>Project Status</th>
                                <!-- <th>Project Type</th> -->
                                <th>Action</th>
                                <!-- <th>Designs</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        @if ($projectQuery->count() == 0)
                        <tr>
                            <td colspan="5">No Projects to display.</td>
                        </tr>
                        @endif
                            @foreach($projectQuery as $data)
                            @if($data->designs->count()==0)
                            <tr>
                                
                                <td>{{ $data->name }}</td>
                                <td> No Design</td>
                                <td>
                                    @if($data->engineer_id == "")
                                        <span class="helper-text red-text">Not Assigned</span>
                                    @else
                                        {{ $data->engineer->first_name }} {{ $data->engineer->last_name }}
                                    @endif
                                </td>
                                <td>
                                    @if($data->engineer_id == "")
                                        <span class="helper-text red-text">Not Assigned</span>
                                    @else
                                        {{ Carbon\Carbon::parse($data->assigned_date)->format('M d, Y') }}
                                    @endif
                                </td>
                                <td class="capitalize">
                                @if($data->status == 'in active')
                                    <span class="label label-red capitalize"> {{ $data->status }}</span>
                                @else
                                    <span class="label label-success capitalize"> {{ $data->status }}</span>
                                @endif</td>

                                </td>   
                                <td class="capitalize">
                                    <span class="label label-inverse capitalize"> - </span>
                                </td>
                               
                                <td class="center">
                                <a class='dropdown-trigger white black-text' href='#' data-target='action{{ $data->id }}'><i class="ti-view-list"></i></a>
                                    <ul id='action{{$data->id}}' class='dropdown-content'>
                                        <li><a href="#assignModel" onclick="setProjectID('{{ $data->name }}',{{$data->id}},{{$data->id}})" class="blue-text modal-trigger">Assign</a></li>
                                        <li><a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $data->id) }}@else{{ route('manager.projects.edit', $data->id) }}@endif" class="indigo-text">Edit</a></li>
                                        <li>
                                            <form id="archiveForm{{$data->id}}" action="{{route('project.archive', $data->id)}}" method="post">
                                                @csrf
                                                
                                            </form>
                                            <a onclick="archiveProject({{$data->id}})" class="imperial-red-text ">Archive</a>
                                        </li>
                                    </ul>
                                   
                                </td>
                               
                            </tr>
                            @endif

                            @foreach($data->designs as $design)
                            <tr>
                                <!-- <td class="center">
                                   <p><label>
                                        <input type="checkbox" class="filled-in checkboxAll" id="{{ $data->id }}" value="{{ $data->id }}"/>
                                        <span> </span>
                                    </label></p>
                                </td> -->
                                <td>{{ $data->name }}</td>
                                <td>{{ $design->type->name }}</td>
                                <td>
                                    @if($data->engineer_id == "")
                                        <span class="helper-text red-text">Not Assigned</span>
                                    @else
                                        {{ $data->engineer->first_name }} {{ $data->engineer->last_name }}
                                    @endif
                                </td>
                                <td>
                                    @if($data->engineer_id == "")
                                        <span class="helper-text red-text">Not Assigned</span>
                                    @else
                                        {{ Carbon\Carbon::parse($data->assigned_date)->format('M d, Y') }}
                                    @endif
                                </td>
                        
                                <td class="capitalize">
                                @if($data->status == 'in active')
                                    <span class="label label-red capitalize">{{ $data->status }}</span>
                                @else
                                    <span class="label label-success capitalize">{{ $data->status }}</span>
                                @endif</td>

                                <td class="capitalize">
                                    <span class="label label-success capitalize"> {{$design->status_engineer}} </span>
                                </td>
                                <td class="center">
                                <a class='dropdown-trigger white black-text' href='#' data-target='action{{ $design->id }}'><i class="ti-view-list"></i></a>
                                    <ul id='action{{$design->id}}' class='dropdown-content'>
                                        <li><a href="#assignModel" onclick="setProjectID('{{ $data->name }}',{{$data->id}},{{$design->id}})" class="blue-text modal-trigger">Assign</a></li>
                                        <li><a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $data->id) }}@else{{ route('manager.projects.edit', $data->id) }}@endif" class="indigo-text">Edit</a></li>
                                        <li>
                                            <form id="archiveForm{{$data->id}}" action="{{route('project.archive', $data->id)}}" method="post">
                                                @csrf
                                                
                                            </form>
                                            <a onclick="archiveProject({{$data->id}})" class="imperial-red-text ">Archive</a>
                                        </li>
                                    </ul>
                                   
                                </td>
                                <!-- <td>
                                <button type="submit" class="btn indigo">Design </button>
                                </td> -->
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
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

@section('js')
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

    $(".checkboxAll").click(function(){
        // alert("Hey!");
        if(this.checked){
           
            checkID.push(this.value);
        }
        else{
            checkID.splice(checkID.indexOf(this.value),1);
        }
        if(checkID.length>0)
        {
            $("#archive").css("display","block");
            $("#assign").css("display","block");
        }
        else{
            $("#archive").css("display","none");
            $("#assign").css("display","none");
        }
    });
    $("#selectAll").click(function(){
        checkID = [];
        if(this.checked){
            // alert("checked");
            $(".checkboxAll").each(function(id, checkboxValue) {
                $(".checkboxAll").prop("checked", true);
                //console.log(checkboxValue.value);               
                checkID.push(checkboxValue.value);
            });
            console.log(checkID);
        }else{
            $(".checkboxAll").each(function(id, checkboxValue) {
                $(".checkboxAll").prop("checked", false);
                checkID.splice(checkID.indexOf(checkboxValue.value),1);
            });
        }
        if(checkID.length>0)
        {
            $("#archive").css("display","block");
            $("#assign").css("display","block");
        }
        else{
            $("#archive").css("display","none");
            $("#assign").css("display","none");
        }
    });
    
</script>
@endsection