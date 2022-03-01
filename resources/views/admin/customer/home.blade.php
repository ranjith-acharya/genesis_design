@extends('layouts.app')

@section('title')
Customer Details - Genesis Design
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
                            <h3>List of Customers</h3>
                        </div>
                        <div class="col s6">
                            <div class="right-align">
                                <button data-target="createCustomer" class="btn indigo modal-trigger"><i class="material-icons left">add</i>NEW CUSTOMER</button>
                            </div>
                        </div>
                    </div>
                    <table id="zero_config" class="responsive-table display">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Company Name</th>
                                <th>Created On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $data)
                            <tr>
                                <td>{{ $data->first_name }} {{ $data->last_name }}</td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->company }}</td>
                                <td>{{ Carbon\Carbon::parse($data->created_at)->format('d M Y') }}</td>
                                <td>
                                    <a href="@if(Auth::user()->role == 'admin'){{ route('admin.customer.edit', $data->id) }}@else{{ route('manager.customer.edit', $data->id) }}@endif"><button type="submit" class="btn-small blue"><i class="material-icons">edit</i></button></a>
                                    <button type="submit" class="btn-small red"><i class="material-icons">delete</i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="createCustomer" class="modal">
                    <div class="modal-content center">
                        <h4>Add new Customer</h4>
                        <form class="center" method="post" action="@if(Auth::user()->role == 'admin'){{ route('admin.customer.store') }}@else{{ route('manager.customer.store') }}@endif">
                            @csrf
                            <div class="row">
                                <div class="col s6">
                                    <div class="input-field w100 col">
                                        <input id="first_name" type="text" class="validate @error('first_name') invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first name">
                                        <label for="first_name">First Name</label>
                                        @error('first_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col s6">
                                    <div class="input-field w100 col">
                                        <input id="last_name" type="text" class="validate @error('last_name') invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last name">
                                        <label for="last_name">Last Name</label>
                                        @error('last_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s6">
                                    <div class="input-field w100 col">
                                        <input id="company" type="text" class="" name="company" value="{{ old('company') }}" autocomplete="company">
                                        <label for="company">Company Name</label>
                                    </div>
                                </div>
                                <div class="col s6">
                                    <div class="input-field w100 col">
                                        <input id="phone" type="text" class="validate @error('phone') invalid @enderror" name="phone" value="{{ old('phone') }}" required maxlength="10" autocomplete="phone">
                                        <label for="phone">Phone Number</label>
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s6">
                                    <div class="input-field w100 col">
                                        <input id="email" type="text" class="validate @error('email') invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                        <label for="email">Email Address</label>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col s6">
                                    <div class="input-field w100 col">
                                        <input id="password" type="password" class="validate @error('password') invalid @enderror" name="password" required autocomplete="password">
                                        <label for="password">Password</label>
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn green">Create</button>
                                <button type="reset" class="modal-close btn red">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </dv>
        </div>
    </div>
</div>
@endsection