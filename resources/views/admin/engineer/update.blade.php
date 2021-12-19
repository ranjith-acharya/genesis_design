@extends('layouts.app')

@section('title')
Edit Customer - {{ $user->first_name }} {{ $user->last_name }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <h3>Engineer Info</h3>
                    <form class="center" method="post" action="{{ route('admin.engineer.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field w100 col">
                                    <input id="first_name" type="text" class="validate @error('first_name') invalid @enderror" name="first_name" value="{{ $user->first_name }}" required autocomplete="first name">
                                    <label for="first_name">First Name</label>
                                    @error('first_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field w100 col">
                                    <input id="last_name" type="text" class="validate @error('last_name') invalid @enderror" name="last_name" value="{{ $user->last_name }}" required autocomplete="last name">
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
                                    <input id="company" type="text" class="" name="company" value="{{ $user->company }}" autocomplete="company">
                                    <label for="company">Company Name</label>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field w100 col">
                                    <input id="phone" type="text" class="validate @error('phone') invalid @enderror" name="phone" value="{{ $user->phone }}" required maxlength="10" autocomplete="phone">
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
                                    <input id="email" type="text" class="validate @error('email') invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email" disabled>
                                    <label for="email">Email Address</label>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn green">Update</button>
                            <a href="{{ route('admin.engineer.index') }}"><button type="button" class="btn red">Cancel</button></a>
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection