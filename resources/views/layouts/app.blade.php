<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{env('APP_NAME')}}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{asset("apple-touch-icon.png")}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset("favicon-32x32.png")}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset("favicon-16x16.png")}}">
    <link rel="manifest" href="{{asset('site.webmanifest')}}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/icon?family=Manrope|Montserrat" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('materialize/css/materialize.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/spaces.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/light.min.css') }}" rel="stylesheet">
    @yield('css')
    @stack('stylesheets')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<nav>
    <div class="nav-wrapper prussian-blue">
        <div class="container">
            <a href="{{route('home')}}" class="left brand-log logo">
                <img src="{{asset('images/logo.png')}}" class="hide-on-small-and-down mt-xxs" alt="GenesisDesign.io Logo" width="220">
                <img src="{{asset('images/logo.png')}}" class="mt-xs hide-on-med-and-up mt-xxxs" alt="GenesisDesign.io Logo" width="120">
            </a>
            @if (Auth::user())
                <ul class="left hide-on-med-and-down">
                    <li class="@if(Route::is('home')) {{"active"}} @endif"><a href="{{route('home')}}">Projects</a></li>
                </ul>
                <ul class="right hide-on-med-and-down">
                    <li><a class="dropdown-trigger" data-target="nav_user_dropdown">{{Auth::user()->email}}</a></li>
                </ul>
                <ul class="right">
                    <li><a class="dropdown-trigger" data-target="nav_notifications"><i class="fal fa-envelope"></i> <span class="new badge imperial-red" id="notification_count" style="display: none"></span></a></li>
                    <li><a href="#" data-target="mobile-demo" class="sidenav-trigger right"><i class="fal fa-bars"></i></a></li>
                </ul>
            @endif
        </div>
    </div>
</nav>
<ul id="nav_notifications" class="dropdown-content"></ul>
<ul id="nav_user_dropdown" class="dropdown-content">
    @if (Auth::user() && Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER)
        <li><a href="{{route('profile.main')}}">Profile</a></li>
        <li><a href="{{route('profile.payment.methods')}}">Payment Methods</a></li>
        <li class="divider"></li>
    @elseif(Auth::user() && Auth::user()->role === \App\Statics\Statics::USER_TYPE_ADMIN)
    <li><a href="{{route('profile.main')}}">Profile</a></li>
        <li><a href="{{route('admin.list',['role'=> \App\Statics\Statics::USER_TYPE_ENGINEER])}}">Engineer</a></li>
        <li><a href="{{route('admin.list',['role'=> \App\Statics\Statics::USER_TYPE_CUSTOMER])}}">Customers</a></li>
        <li><a href="{{route('home')}}">Projects</a></li>
        <li class="divider"></li>
    @endif
    <li><a href="{{route('logout')}}">Log-Out</a></li>
</ul>
<ul class="sidenav" id="mobile-demo">
    @if (Auth::user())
        @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER)
            <li><a href="{{route('profile.main')}}">Profile</a></li>
            <li><a href="{{route('profile.payment.methods')}}">Payment Methods</a></li>
            @elseif(Auth::user() && Auth::user()->role === \App\Statics\Statics::USER_TYPE_ADMIN)
        <li><a href="{{route('profile.main')}}">Profile</a></li>
        <li><a href="{{route('profile.payment.methods')}}">Engineer</a></li>
        <li><a href="{{route('profile.payment.methods')}}">Customers</a></li>
        <li><a href="{{route('home')}}">Projects</a></li>
        <li class="divider"></li>
        @endif
        <li><a href="{{route('logout')}}">Log-out</a></li>
    @endif
</ul>
<main>
    @yield('content')
</main>
<footer class="page-footer prussian-blue">
    <div class="container">
        @yield('footer')
    </div>
    <div class="footer-copyright">
        <div class="container center">
            © {{env('APP_NAME')}} LLC 2020. All rights reserved.
        </div>
    </div>
</footer>
<script type="text/javascript" src="{{asset('materialize/js/materialize.min.js')}}" defer></script>
<script src="{{asset('js/handlebars.min.js')}}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        M.Sidenav.init(document.querySelectorAll('.sidenav'));
        M.FormSelect.init(document.querySelectorAll('select'));
        M.Tooltip.init(document.querySelectorAll('.tooltipped'));
        M.Dropdown.init(document.querySelectorAll('main .dropdown-trigger'));

        M.Dropdown.init(document.querySelectorAll('nav .dropdown-trigger'), {
            alignment: "right",
            constrainWidth: false,
            coverTrigger: false
        });

        @if (!Route::is('login') && !Route::is('register'))
        loadNotifications()
        @endif
    });

    @if (!Route::is('login') && !Route::is('register'))
    function loadNotifications() {
        axios('{{route('messages.unread')}}').then(response => {
            if (response.data.length > 0) {
                let template = Handlebars.compile(document.getElementById('notification_template').innerText);
                document.getElementById('nav_notifications').innerHTML = template({data: response.json});
                document.getElementById('notification_count').innerHTML = response.json.length;
                document.getElementById('notification_count').style.display = 'inline-block';
            } else {
                document.getElementById('nav_notifications').innerHTML = document.getElementById('no_notification_template').innerText;
                document.getElementById('notification_count').style.display = 'none';
                document.getElementById('notification_count').innerHTML = "";
            }
        });
    }
    @endif

    // Debounce function call
    function debouncer(func, wait, immediate) {
        let timeout;
        return function () {
            let context = this,
                args = arguments;

            // remove specials
            this.value = this.value.replace(/[^\w\s]/gi, "");

            // remove extra spaces
            this.value = this.value.replace(/ +(?= )/g, "");

            let callNow = immediate && !timeout;

            clearTimeout(timeout);

            timeout = setTimeout(function () {
                timeout = null;
                if (!immediate) {
                    func.apply(context, args);
                }
            }, wait);

            if (callNow) func.apply(context, args);
        };
    }
</script>
<script type="text/html" id="notification_template">
    @{{#each data}}
    <li class="p"><a href="{{route('design.view', '')}}/@{{this.design}}" class="pt-xxs pb-xxs steel-blue-text">New message for @{{ this.design_type }} <br> For project: @{{ project }}<span
                class="center-block right-align extra-small imperial-red-text">@{{this.time}}</span></a></li>
    @{{/each}}
</script>
<script type="text/html" id="no_notification_template">
    <li class="p"><a class="pt-xxs pb-xxs">No new notifications</a></li>
</script>
@yield('js')
@stack('scripts')
</body>
</html>
