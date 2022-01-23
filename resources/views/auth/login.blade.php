<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <title>Genesis Design - Login</title>
    <link href="{{ asset('dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/pages/authentication.css') }}" rel="stylesheet">
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Genesis Design</p>
            </div>
        </div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url({{ asset('assets/images/big/auth-bg.jpg') }}) no-repeat center center;">
            <div class="auth-box">
                <div id="loginform" >
                    <div class="logo">
                        <span class="db"><img src="{{ asset('assets/images/logo-icon-1.png') }}" alt="logo" height="50px" width="60px"/></span>
                        <h5 class="font-medium m-b-20">Sign in to continue <br> Genesis Design</h5>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <form class="col s12" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="email" type="email" class="validate @error('email') invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                                    <label for="email">Email</label>
                                    @error('email')
                                        <span class="helper-text red-text" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <!-- pwd -->
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="password" type="password" class="validate @error('password') invalid @enderror" name="password" required autocomplete="current-password">
                                    <label for="password">Password</label>
                                    @error('password')
                                        <span class="helper-text red-text"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <!-- pwd -->
                            <div class="row m-t-5">
                                <div class="col s7">
                                    <label>
                                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span>Remember Me?</span>
                                    </label>
                                </div>
                                <div class="col s5 right-align">
                                    <a href="#" class="link" id="to-recover">Forgot Password?</a>
                                </div>
                            </div>
                            <!-- pwd -->
                            <div class="row m-t-40">
                                <div class="col s12">
                                    <button class="btn-large w100 blue accent-4" type="submit">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="center-align m-t-20 db">
                        Don't have an account? <a href="{{ route('register') }}">Sign Up!</a>
                    </div>
                </div>
                <div id="recoverform">
                    <div class="logo">
                        <span class="db"><img src="{{ asset('assets/images/logo-icon-1.png') }}" alt="logo" height="50px" width="60px" /></span>
                        <h5 class="font-medium m-b-20">Recover Password</h5>
                        <span>Enter your Email and instructions will be sent to you!</span>
                    </div>
                    <div class="row">
                        <!-- Form -->
                        <form class="col s12" method="POST" action="{{ route('password.email') }}">
                        @csrf
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="email1" type="email" class="validate @error('email') invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    <label for="email1">Email</label>
                                    @error('email')
                                    <span class="helper-text red-text">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- pwd -->
                            <div class="row m-t-20">
                                <div class="col s6">
                                    <button class="btn-large w100 blue" type="submit" name="action">Reset</button>
                                </div>
                                <div class="col s6">
                                    <a href="{{ route('login') }}">
                                        <button class="btn-large w100 red" type="button">Cancel</button>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/js/materialize.min.js') }}"></script>
    <script>
    $('.tooltipped').tooltip();
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    $(function() {
        $(".preloader").fadeOut();
    });
    </script>
</body>
</html>