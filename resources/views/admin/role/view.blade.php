@extends('layouts.app')

@section('title')
Details {{ $role->name }} - Design Genesis
@endsection

@section('content')
<div class="container-fluid">
    <div class="col s4">
        <div class="card">
        @if ($message = Session::get('success'))
            <script>
                toastr.success('{{$message}}', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
            </script>
	    @endif
            <div class="card-content">
                <h5 class="card-title capitalize">About {{ $role->name }} Role Info</h5><br>
            <form class="form" method="POST" action="{{ route('admin.roles.update', $role->id) }}">
			@csrf
            @method('PUT')
            <div class="input-field">
                <input id="role_name" type="text" class="validate @error('role_name') invalid @enderror" name="role_name" value="{{ $role->name }}" required autocomplete="role name">
                <label for="role_name">Role Name</label>
                @error('role_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <table class="responsive-table">
                <tr>
            @foreach($permissions as $key => $value)
                <td>
                    <label class="">
					    {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolehasPermissions) ? true : false, array('class' => 'filled-in')) }}
    				    <span class="black-text">{{ $value->name }}</span>
                    </label>
                </td>
                @if(($key+1)%4==0)
                    </tr><tr>
                @endif
            @endforeach
                </tr>
            </table>
            <button type="submit" class="m-t-20 btn waves-effect waves-light teal">Update</button>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection