@extends('layouts.app')

@section('title', "Login")

@section('js')
    <script src="{{asset('js/vanta/three.r92.min.js')}}"></script>
    <script src="{{asset('js/vanta/vanta.net.min.js')}}"></script>
    <script type="text/javascript">
        VANTA.NET({
            el: "body",
            backgroundColor: "#f5f5f5",
            color: "#E63946",
            touchControls: true,
            mouseControls: true,
            minHeight: 200.00,
            minWidth: 200.00,
            scaleMobile: 1.00,
            points: 9.00,
            maxDistance: 17.00,
            spacing: 13.00
        });


        document.getElementById('toggle-pass').addEventListener('click', function (event) {
            let button = event.target;

            if (button.getAttribute('data-mode') === "hide") {
                button.setAttribute('data-mode', "show");
                document.getElementById('password').type = "text";
                document.getElementById('pass-toggle-image').classList.remove('fa-eye-slash');
                document.getElementById('pass-toggle-image').classList.add('fa-eye');
            } else {
                button.setAttribute('data-mode', "hide");
                document.getElementById('password').type = "password";
                document.getElementById('pass-toggle-image').classList.remove('fa-eye');
                document.getElementById('pass-toggle-image').classList.add('fa-eye-slash');
            }
        })
    </script>
@endsection

@section('content')
    <div class="valign-wrapper" style="width:100%;height:90%;position: absolute;">
        <div class="container">
            <div class="row justify-content-center ">
                <div class="col s12 m8 offset-m2">
                    <div class="card" style="background-color:#ffffffd9;">
                        <div class="center">
                            <h4 class="card-header imperial-red-text" style="padding-top: 20px;text-transform: capitalize">Sign In</h4>
                        </div>
                        <div class="card-content">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="input-field row">
                                    <label for="email" class="col m4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                    <input id="email" type="email" class="@error('email') invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="helper-text red-text" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="row center">
                                    <div class="input-field col s10 m11">
                                        <label for="password">{{ __('Password') }}</label>
                                        <input id="password" type="password" class="@error('password') invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                        <span class="helper-text red-text"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="col s2 m1 valign-wrapper center" style="height: 84px;justify-content: center;">
                                        <a class="steel-blue-text hover-red" id="toggle-pass"><i class="fal fa-eye-slash fa-2x" data-mode="hide" id="pass-toggle-image"></i></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12 m6 left">
                                        <label for="remember">
                                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span class="form-check-label">{{ __('Remember Me') }}</span>
                                        </label>
                                    </div>
                                    <div class="col s12 m6 hide-on-small-and-down">
                                        @if (Route::has('password.request'))
                                            <a class="right steel-blue-text hover-red" href="{{ route('password.request') }}"> {{ __('Forgot Your Password?') }}</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12 center">
                                        <button type="submit" class="btn btn-large steel-blue-outline-button">Sign In</button>
                                        <a type="submit" class="btn btn-large steel-blue-outline-button ml-s" href="{{route('register')}}">Register</a>
                                        @if (Route::has('password.request'))
                                            <div class="hide-on-med-and-up mt-s">
                                                <a class="steel-blue-text hover-red" href="{{ route('password.request') }}"> {{ __('Forgot Your Password?') }}</a>
                                            </div>
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
