@extends('layouts.app')

@section('title', "Request Password Reset")

@section('content')
    <div class="valign-wrapper" style="width:100%;height:80%;position: absolute;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col s12 m8 offset-m2">
                    <div class="card">
                        <div class="center">
                            <h4 class="card-header imperial-red-text" style="padding-top: 20px;text-transform: capitalize">{{ __('Reset Password') }}</h4>
                        </div>
                        <div class="card-content center">
                            @if (session('status'))
                                <div class="alert alert-success green-text mb-m" style="font-weight: 400;font-size: 20px" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="input-field row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                    <input id="email" type="email" class="form-control @error('email') invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="helper-text red-text">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn steel-blue-outline-button">
                                            {{ __('Send Password Reset Link') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
