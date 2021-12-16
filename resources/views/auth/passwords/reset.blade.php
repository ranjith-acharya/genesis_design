@extends('layouts.app')

@section('title', "Reset Password")

@section('content')
    <div class="valign-wrapper" style="width:100%;height:80%;position: absolute;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col s12 m8 offset-m2">
                    <div class="card">
                        <div class="center">
                            <h4 class="card-header imperial-red-text" style="padding-top: 20px;text-transform: capitalize">{{ __('Reset Password') }}</h4></div>

                        <div class="card-content">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="input-field row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>


                                    <input id="email" type="email" class="form-control @error('email') invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="helper-text red-text" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="input-field row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>


                                    <input id="password" type="password" class="form-control @error('password') invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="helper-text red-text" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                                <div class="input-field row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>


                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

                                </div>

                                <div class="form-group row mb-0 center">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn steel-blue-outline-button">
                                            {{ __('Reset Password') }}
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
