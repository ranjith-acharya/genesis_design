@extends('layouts.app')

@section('title')
Roles - Genesis Design
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
                            <h3>List of Roles</h3>
                        </div>
                        <div class="col s6">
                            <div class="right-align">
                                <button data-target="createRole" class="btn indigo modal-trigger"><i class="material-icons left">add</i>NEW ROLE</button>
                            </div>
                        </div>
                    </div>
                    <table id="zero_config" class="responsive-table display">
                        <thead>
                            <tr>
                                <th>Role Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <a href="{{ route('admin.roles.show', $role->id) }}">
                                        <button type="submit" class="btn-small blue @if($role->name == 'admin') hide @endif"><i class="material-icons">edit</i></button>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="createRole" class="modal">
                    <div class="modal-content">
                        <h4>Add new Role</h4>
                        <form class="center" method="post" action="{{ route('admin.roles.store') }}">
                            @csrf
                            <table class="table responsive-table">
                            <div class="input-field">
                                <input id="role_name" type="text" class="validate @error('role_name') invalid @enderror" name="role_name" value="{{ old('role_name') }}" required autocomplete="role name">
                                <label for="role_name">Role Name</label>
                                @error('role_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <tr>
                            @foreach($permissions as $key => $value)
                                <td>
                                    <label>
                                        <input type="checkbox" class="filled-in" value="{{ $value->id }}" name="permission[]" />
                                        <span class="black-text">{{ $value->name }}</span>
                                    </label>                                        
                                </td>
                                    @if(($key+1)%4==0)
                                </tr>
                            <tr>
                            @endif
                            
                            @endforeach
                            </tr>
                           <tr><td>
                            <div class="modal-footer">
                                <div class="row">
                                    <div class="col s6">
                                        <button type="submit" class="btn green">Create</button>
                                    </div>
                                    <div class="col s6">
                                        <button type="reset" class="modal-close btn red">Cancel</button>
                                    </div>
                                </div>
                            </div></td></tr>
                            </table>
                        </form>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>                
@endsection