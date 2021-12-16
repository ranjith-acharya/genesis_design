@extends('layouts.app')

@section('title', "Verify")

@section('content')
    <div class="valign-wrapper" style="width:100%;height:80%;position: absolute;">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col s12 m8 offset-m2">
                    <div class="card">
                        <div class="center">
                        <h4 class="card-header imperial-red-text" style="padding-top: 20px;text-transform: capitalize">{{ __('Verify Your Email Address') }}</h4>
                        </div>
                        <div class="card-content center">
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            @endif

                            {{ __('Before proceeding, please check your email for a verification link.') }}
                            {{ __('If you did not receive the email') }},
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn steel-blue-outline-button mt-s">{{ __('click here to request another') }}</button>
                                .
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
