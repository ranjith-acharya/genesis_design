@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 3em">
        <div class="row">
            <div class="col s12 m3">
                <div class="collection z-depth-0 profile-menu">
                    <a href="{{route('profile.main')}}" class="collection-item steel-blue-text @if(Route::is('profile.main')) active @endif">My Profile</a>
                    <a href="{{route('profile.payment.methods')}}" class="collection-item steel-blue-text @if(Route::is('profile.payment.methods')) active @endif">Payment Methods</a>
                </div>
            </div>
            <div class="col s12 m9">
                @yield('profile_body')
            </div>
        </div>
    </div>
@endsection
