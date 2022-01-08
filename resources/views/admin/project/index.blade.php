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
                        <div class="col s6">
                            <h3>List of Projects</h3>
                        </div>
                        <div class="col s6 right-align">
                    
                    
                    <!-- <button class="btn indigo dropdown-trigger" data-target='dropdown1'><i class="material-icons left">add</i>NEW PROJECT</button>
                        <ul id='dropdown1' class='dropdown-content'>
                            <li><a href="http://127.0.0.1:8000/project/new/residential">Residential</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="http://127.0.0.1:8000/project/new/commercial">Commercial</a></li>
                            <li class="divider" tabindex="-1"></li>
                        </ul> -->                   
                        </div>
                    </div>
                    <table id="zero_config" class="responsive-table display">
                        <thead>
                            <tr class="black-text">
                                <th>Project Name</th>
                                <th> Assigned To</th>
                                <th>Project Status</th>
                                <th>Project Type</th>
                                <th>Action</th>
                                <!-- <th>Designs</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projectQuery as $data)
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->engineer->first_name }} {{ $data->engineer->last_name }}</td>
                                <td class="capitalize">
                                @if($data->status == 'pending')
                                    <span class="label label-red capitalize">{{ $data->status }}</span>
                                @else
                                    <span class="label label-success capitalize">{{ $data->status }}</span>
                                @endif</td>
                                <td class="capitalize">{{ $data->type->name }}</td>
                                <td class="center">
                                <a class='dropdown-trigger white black-text' href='#' data-target='action{{ $data->id }}'><i class="ti-view-list"></i></a>
                                    <ul id='action{{$data->id}}' class='dropdown-content'>
                                        <li><a href="#assign" onclick="setProjectID('{{ $data->name }}',{{$data->id}})" class="blue-text modal-trigger">Assign</a></li>
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
                        </tbody>
                    </table>
                    <div id="assign" class="modal modal-fixed-footer">
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
    function setProjectID(name,id)
    {
        $('#project_id').val(id);
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
</script>
@endsection