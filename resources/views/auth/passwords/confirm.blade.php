@extends('layouts.app')

@section('title', "Confirmation")

@section('content')
    <div class="valign-wrapper" style="width:100%;height:80%;position: absolute;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col s12 m8 offset-m2">
                    <div class="card">
                        <div class="center">
                            <h4 class="card-header imperial-red-text" style="padding-top: 20px;text-transform: capitalize">{{ __('Confirm Password') }}</h4>
                        </div>
                        <div class="card-content">
                            <div class="center">
                                {{ __('Please confirm your password before continuing.') }}
                            </div>
                            <form method="POST" action="{{ route('password.confirm') }}">
                                @csrf

                                <div class="input-field row">
                                    <div class="col s12">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                                        <input id="password" type="password" class="form-control @error('password') invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                        <span class="helper-text red-text" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row center">
                                    <div class="col s12">
                                        <button type="submit" class="btn steel-blue-outline-button">
                                            {{ __('Confirm Password') }}
                                        </button>
                                        @if (Route::has('password.request'))
                                            <a class="btn steel-blue-outline-button m-xxs" href="{{ route('password.request') }}">
                                                {{ __('Forgot Password?') }}
                                            </a>
                                        @endif
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
