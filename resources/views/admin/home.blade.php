@extends('layouts.app')

@section('title')
Admin Home - Design Genesis
@endsection

@section('content')
<div class="container-fluid">
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
                    <div class="col s6">
                    <div class="right-align">
                        
                    <button class="btn indigo dropdown-trigger" data-target='dropdown1'><i class="material-icons left">add</i>NEW PROJECT</button>
                        <ul id='dropdown1' class='dropdown-content'>
                            <li><a href="http://127.0.0.1:8000/project/new/residential">Residential</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="http://127.0.0.1:8000/project/new/commercial">Commercial</a></li>
                            <li class="divider" tabindex="-1"></li>
                        </ul>
                    </div>
                    </div>
                    </div>
                    <table id="zero_config" class="responsive-table display">
                        <thead>
                            <tr>
                                <th>Project Name</th>
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
                                <td class="capitalize">{{ $data->status }}</td>
                                <td class="capitalize">{{ $data->type->name }}</td>
                                <td class="center">
                                <a class='dropdown-trigger white black-text' href='#' data-target='action{{ $data->id }}'><i class="ti-view-list"></i></a>
                                    <ul id='action{{$data->id}}' class='dropdown-content'>
                                        <li><a href="#assign{{$data->id}}" onclick="setProjectID({{ $data->id }})" class="blue-text modal-trigger">Assign</a></li>
                                        <li><a href="{{ route('admin.projects.edit', $data->id) }}" class="indigo-text">Edit</a></li>
                                        <li><a href="" class="imperial-red-text">Delete</a></li>
                                    </ul>
                                </td>
                                <!-- <td>
                                <button type="submit" class="btn indigo">Design </button>
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div id="assign{{ $data->id }}" class="modal modal-fixed-footer">
                        <div class="modal-content">
                            <h4>Select Engineer to Assign {{ $data->name }} project</h4>
                            <form method="post" id="assign_form">
                            @csrf
                                <div class="input-field col s12">
                                    <select multiple name="engineer_id">
                                        <option value="" disabled>Select Engineer</option>
                                        @foreach($engineers as $engineer)
                                        <option value="{{ $engineer->id }}">{{ $engineer->first_name }} {{ $engineer->last_name }}</option>
                                        @endforeach
                                    </select>
                                    <label>Select Engineer</label>
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
    function setProjectID(id){
        $('#project_id').val(id);
        $("#assign_form").attr('action',"{{ route('admin.assign') }}");
        //projects/assign/'+id+'
        //alert(id);
    }
</script>
@endsection