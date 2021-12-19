<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <title>Design Genesis - Login</title>
    <link href="{{ asset('dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/pages/authentication.css') }}" rel="stylesheet">
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Design Genesis</p>
            </div>
        </div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url({{ asset('assets/images/big/auth-bg.jpg') }}) no-repeat center center;">
            <div class="valign-wrapper" style="width:100%;height:80%;position: absolute;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col s12 m8 offset-m2">
                            <div class="card">
                                <div class="center">
                                    <h4 class="card-header imperial-red-text" style="padding-top: 20px;text-transform: capitalize">{{ __('Verify Your Email Address') }}</h4>
                                </div>
                                <div class="card-content container">
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
                                        <br>
                                        <div class="form-group row mb-0 center">
                                            <div class="col-md-6 offset-md-4">
                                                <button type="submit" class="btn indigo">
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