@extends('layouts.app')

@section('title')
Admin Home - Design Genesis
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col s12">
            <div class="card">
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
                                <td>{{ $data->status }}</td>
                                <td>{{ $data->type->name }}</td>
                                <td>
                                    <a href="{{ route('admin.projects.edit', $data->id) }}"><button type="submit" class="btn-small blue"><i class="material-icons">edit</i></button></a>
                                    <button type="submit" class="btn-small red"><i class="material-icons">delete</i></button>
                                </td>
                                <!-- <td>
                                <button type="submit" class="btn indigo">Design </button>
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection