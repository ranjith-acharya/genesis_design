<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('site.webmanifest')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <link href="{{ asset('materialize/css/materialize.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/spaces.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/light.min.css') }}" rel="stylesheet">
    <link href="{{ asset('uppy/uppy.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/extra-libs/prism/prism.css') }}" rel="stylesheet">
    @yield('css')
    @stack('stylesheets')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/pages/data-table.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/pages/dashboard1.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/toastr/build/toastr.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/js/materialize.min.js') }}"></script>
    <script src="{{ asset('assets/libs/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/toastr/toastr-init.js') }}"></script>
    <link href="{{ asset('assets/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/jquery-steps/steps.css') }}" rel="stylesheet">
</head>
<style> label{color:#000 !important;} </style>
<body>
    <div class="main-wrapper" id="main-wrapper">
        <header class="topbar">
            <nav>
                <div class="nav-wrapper">
                    <a href="@if(Auth::user()->role == 'admin') {{ route('admin.home') }} @elseif(Auth::user()->role == 'manager') {{ route('manager.home.index') }} @else {{ route('home') }} @endif" class="brand-logo">
                        <span class="icon">
                            <img class="light-logo" src="{{ asset('assets/images/logo-icon-white.png') }}" height="30px" width="40px" style="margin-top:4px;">
                        </span>
                        <span class="text">
                            <img class="light-logo" src="{{ asset('assets/images/logo-text-white.png') }}" height="40px" style="margin-top:22px;margin-left:15px;">
                        </span>
                    </a>
                    <ul class="left">
                        <li class="hide-on-med-and-down">
                            <a href="javascript: void(0);" class="nav-toggle">
                                <span class="bars bar1"></span>
                                <span class="bars bar2"></span>
                                <span class="bars bar3"></span>
                            </a>
                        </li>
                        <li class="hide-on-large-only">
                            <a href="javascript: void(0);" class="sidebar-toggle">
                                <span class="bars bar1"></span>
                                <span class="bars bar2"></span>
                                <span class="bars bar3"></span>
                            </a>
                        </li>
                        <li><a class="dropdown-trigger" href="javascript: void(0);" data-target="noti_dropdown"><i class="material-icons">notifications</i></a>
                            <ul id="noti_dropdown" class="mailbox dropdown-content">
                                <li>
                                    <div class="drop-title">Notifications</div>
                                </li>
                                <li>
                                    <div class="message-center">
                                        <a href="#">
                                                <span class="btn-floating btn-large red"><i class="material-icons">link</i></span>
                                                <span class="mail-contnet">
                                                    <h5>Launch Admin</h5>
                                                    <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span>
                                                </span>
                                            </a>
                                        <a href="#">
                                                <span class="btn-floating btn-large blue"><i class="material-icons">date_range</i></span>
                                                <span class="mail-contnet">
                                                    <h5>Event today</h5>
                                                    <span class="mail-desc">Just a reminder that you have event</span>
                                                    <span class="time">9:10 AM</span>
                                                </span>
                                            </a>
                                        <a href="#">
                                                <span class="btn-floating btn-large cyan"><i class="material-icons">settings</i></span>
                                                <span class="mail-contnet">
                                                    <h5>Settings</h5>
                                                    <span class="mail-desc">You can customize this template as you want</span>
                                                    <span class="time">9:08 AM</span>
                                                </span>
                                            </a>
                                        <a href="#">
                                                <span class="btn-floating btn-large green"><i class="material-icons">face</i></span>
                                                <span class="mail-contnet">
                                                    <h5>Lily Jordan</h5>
                                                    <span class="mail-desc">Just see the my admin!</span>
                                                    <span class="time">9:02 AM</span>
                                                </span>
                                            </a>
                                    </div>
                                </li>
                                <li>
                                    <a class="center-align" href="javascript:void(0);"> <strong>Check all notifications</strong> </a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-trigger" href="javascript: void(0);" data-target="msg_dropdown"><i class="material-icons">comment</i></a>
                            <ul id="msg_dropdown" class="mailbox dropdown-content">
                                <li>
                                    <div class="drop-title">You have 4 new messages</div>
                                </li>
                                <li>
                                    <div class="message-center">
                                        <!-- Message -->
                                        <a href="#">
                                                <span class="user-img">
                                                    <img src="{{ asset('assets/images/users/1.jpg') }}" alt="user" class="circle">
                                                    <span class="profile-status online pull-right"></span>
                                                </span>
                                                <span class="mail-contnet">
                                                    <h5>Chris Evans</h5>
                                                    <span class="mail-desc">Just see the my admin!</span>
                                                    <span class="time">9:30 AM</span>
                                                </span>
                                            </a>
                                        <!-- Message -->
                                        <a href="#">
                                                <span class="user-img">
                                                    <img src="{{ asset('assets/images/users/2.jpg') }}" alt="user" class="circle">
                                                    <span class="profile-status busy pull-right"></span>
                                                </span>
                                                <span class="mail-contnet">
                                                    <h5>Ray Hudson</h5>
                                                    <span class="mail-desc">I've sung a song! See you at</span>
                                                    <span class="time">9:10 AM</span>
                                                </span>
                                            </a>
                                        <!-- Message -->
                                        <a href="#">
                                                <span class="user-img">
                                                    <img src="{{ asset('assets/images/users/3.jpg') }}" alt="user" class="circle">
                                                    <span class="profile-status away pull-right"></span>
                                                </span>
                                                <span class="mail-contnet">
                                                    <h5>Lb James</h5>
                                                    <span class="mail-desc">I am a singer!</span>
                                                    <span class="time">9:08 AM</span>
                                                </span>
                                            </a>
                                        <!-- Message -->
                                        <a href="#">
                                                <span class="user-img">
                                                    <img src="{{ asset('assets/images/users/4.jpg') }}" alt="user" class="circle">
                                                    <span class="profile-status offline pull-right"></span>
                                                </span>
                                                <span class="mail-contnet">
                                                    <h5>Don Andres</h5>
                                                    <span class="mail-desc">Just see the my admin!</span>
                                                    <span class="time">9:02 AM</span>
                                                </span>
                                            </a>
                                    </div>
                                </li>
                                <li>
                                    <a class="center-align" href="javascript:void(0);"> <strong>See all e-Mails</strong> </a>
                                </li>
                            </ul>
                        </li>
                        <li class="search-box">
                            <a href="javascript: void(0);"><i class="material-icons">search</i></a>
                            <form class="app-search">
                                <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i class="ti-close"></i></a>
                            </form>
                        </li>
                    </ul>
                    <ul class="right">
                        <li class="lang-dropdown"><a class="dropdown-trigger" href="javascript: void(0);" data-target="lang_dropdown"><i class="flag-icon flag-icon-in"></i></a>
                            <ul id="lang_dropdown" class="dropdown-content">
                                <li>
                                    <a href="#!" class="grey-text text-darken-1">
                                        <i class="flag-icon flag-icon-us"></i> English</a>
                                </li>
                                <li>
                                    <a href="#!" class="grey-text text-darken-1">
                                        <i class="flag-icon flag-icon-fr"></i> French</a>
                                </li>
                                <li>
                                    <a href="#!" class="grey-text text-darken-1">
                                        <i class="flag-icon flag-icon-es"></i> Spanish</a>
                                </li>
                                <li>
                                    <a href="#!" class="grey-text text-darken-1">
                                        <i class="flag-icon flag-icon-de"></i> German</a>
                                </li>
                            </ul>
                        </li>
                        @php
                            $profile_img=Auth::user()->role.".png";
                        @endphp
                        <li><a class="dropdown-trigger" href="javascript: void(0);" data-target="user_dropdown"><img src="{{ asset('assets/images/users/'.$profile_img)}}" alt="user" class="circle profile-pic"></a>
                            <ul id="user_dropdown" class="mailbox dropdown-content dropdown-user">
                                <li>
                                    <div class="dw-user-box">
                                        <div class="u-img"><img src="{{ asset('assets/images/users/'.$profile_img)}}" alt="user"></div>
                                        <div class="u-text">
                                            <h4>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                                            <p>{{ Auth::user()->email }}</p>
                                            <a class="waves-effect waves-light btn-small red white-text" href="{{ route('profile.profile.index') }}">View Profile</a>
                                        </div>
                                    </div>
                                </li>
                                <li role="separator" class="divider"></li>
                                <!-- <li><a href="#"><i class="material-icons">account_circle</i> My Profile</a></li> -->
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
                                    <li><a href="{{route('profile.payment.methods')}}"><i class="material-icons">account_balance_wallet</i> Payment Methods</a></li>
                                @endif
                                <li><a href="#"><i class="material-icons">inbox</i> Inbox</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#"><i class="material-icons">settings</i> Account Setting</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('logout') }}"><i class="material-icons">power_settings_new</i> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="left-sidebar">
            <ul id="slide-out" class="sidenav">     
                <li>
                    <ul class="collapsible">
                        @if(Auth::user()->role === \App\Statics\Statics::USER_TYPE_ADMIN)
                        <li class="small-cap"><span class="hide-menu white-text">ADMINISTRATOR PANEL</span></li>
                        <li><a href="{{ route('admin.home') }}"><i class="material-icons white-text" style="font-size:26px;">home</i><span class="hide-menu white-text" style="font-size:16px;">Dashboard</span></a></li>
                        <li>
                        <a href="javascript: void(0);" class="collapsible-header has-arrow"><i class="material-icons white-text">dashboard</i><span class="hide-menu white-text" style="font-size:16px;"> Users</span></a>
                        <div class="collapsible-body">
                            <ul>
                                <ul>
                                    <li><a href="{{ route('admin.customer.index') }}"><i class="material-icons white-text" style="font-size:26px;">person_pin</i><span class="hide-menu white-text" style="font-size:16px;">Customers</span></a></li>
                                    <li><a href="{{ route('admin.manager.index') }}"><i class="material-icons white-text" style="font-size:26px;">person_pin</i><span class="hide-menu white-text" style="font-size:16px;">Managers</span></a></li>
                                    <li><a href="{{ route('admin.engineer.index') }}"><i class="material-icons white-text" style="font-size:26px;">person_pin</i><span class="hide-menu white-text" style="font-size:16px;">Engineers</span></a></li>
                                    <li><a href="{{ route('admin.users.index') }}"><i class="material-icons white-text" style="font-size:26px;">person_pin</i><span class="hide-menu white-text" style="font-size:16px;">Users</span></a></li>
                                </ul>
                            </ul>
                        </div>
                        </li>
                        <li><a href="{{ route('admin.price.index') }}"><i class="material-icons white-text" style="font-size:26px;">attach_money</i><span class="hide-menu white-text" style="font-size:16px;">Set Price</span></a></li>
                        <li><a href="{{ route('admin.roles.index') }}"><i class="material-icons white-text" style="font-size:26px;">person_pin</i><span class="hide-menu white-text" style="font-size:16px;">Roles</span></a></li>
                        <li><a href="{{ route('admin.projects.list') }}"><i class="material-icons white-text" style="font-size:26px;">next_week</i><span class="hide-menu white-text" style="font-size:16px;">Projects</span></a></li>
                        <li class="divider"></li>
                        @endif
                        @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER)
                            <li class="small-cap"><span class="hide-menu white-text">CUSTOMER PANEL</span></li>
                            <li><a href="{{ route('home') }}"><i class="material-icons white-text" style="font-size:26px;">home</i><span class="hide-menu white-text" style="font-size:16px;">Home</span></a></li>
                            <li><a href="{{route('profile.profile.index')}}"><i class="material-icons white-text" style="font-size:26px;">person_pin</i><span class="hide-menu white-text" style="font-size:16px;">Profile</span></a></li>
                            <li><a href="{{route('customer.export')}}"><i class="material-icons white-text" style="font-size:26px;">assignment</i><span class="hide-menu white-text" style="font-size:16px;">Reports</span></a></li>
                            <!-- <li><a href="{{route('profile.payment.methods')}}"><i class="material-icons white-text" style="font-size:26px;">credit_card</i><span class="hide-menu white-text">Payment Methods</span></a></li> -->
                            <li class="divider"></li>
                        @endif
                        @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_ENGINEER)
                            <li class="small-cap"><span class="hide-menu white-text">ENGINEER PANEL</span></li>
                            <li><a href="{{ route('home') }}"><i class="material-icons white-text" style="font-size:26px;">home</i><span class="hide-menu white-text" style="font-size:16px;">Projects</span></a></li>
                            <!-- <li><a href="{{route('profile.profile.index')}}"><i class="material-icons white-text" style="font-size:26px;">person_pin</i><span class="hide-menu white-text">Profile</span></a></li> -->
                            <!-- <li><a href="{{route('profile.payment.methods')}}"><i class="material-icons white-text" style="font-size:26px;">credit_card</i><span class="hide-menu white-text">Payment Methods</span></a></li> -->
                            <li class="divider"></li>
                        @endif
                        @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_MANAGER)
                            <li class="small-cap"><span class="hide-menu white-text">MANAGER PANEL</span></li>
                            <li><a href="{{ route('manager.home.index') }}"><i class="material-icons white-text" style="font-size:26px;">home</i><span class="hide-menu white-text" style="font-size:16px;">Dashboard</span></a></li>
                            <li><a href="{{ route('manager.projects.list') }}"><i class="material-icons white-text" style="font-size:26px;">next_week</i><span class="hide-menu white-text" style="font-size:16px;">Projects</span></a></li>
                            <!-- <li><a href="{{ route('home') }}"><i class="material-icons white-text" style="font-size:26px;">home</i><span class="hide-menu white-text">Dashboard</span></a></li> -->
                            <li>
                            <a href="javascript: void(0);" class="collapsible-header has-arrow"><i class="material-icons white-text">dashboard</i><span class="hide-menu white-text" style="font-size:16px;"> Users</span></a>
                            <div class="collapsible-body">
                                <ul>
                                    <ul>
                                        <li><a href="{{ route('manager.customer.index') }}"><i class="material-icons white-text" style="font-size:26px;">person_pin</i><span class="hide-menu white-text" style="font-size:16px;">Customers</span></a></li>
                                        <li><a href="{{ route('manager.engineer.index') }}"><i class="material-icons white-text" style="font-size:26px;">person_pin</i><span class="hide-menu white-text" style="font-size:16px;">Engineers</span></a></li>
                                    </ul>
                                </ul>
                            </div>
                            </li>
                            <li class="divider"></li>
                        @endif
                        <li>
                            <a href="{{ route('logout') }}"><i class="material-icons white-text" style="font-size:26px;">power_settings_new</i><span class="hide-menu white-text" style="font-size:16px;"> Log Out </span></a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>
            </ul>
        </aside>
<main>
    <div class="main-wrapper" id="main-wrapper">
        <div class="page-wrapper">
    @yield('content')
        </div>
    </div>
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
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/js/materialize.min.js') }}"></script>
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('dist/js/app.js') }}"></script>
    <script src="{{ asset('dist/js/app.init.js') }}"></script>
    <script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/Datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
    <script src="{{ asset('assets/libs/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/toastr/toastr-init.js') }}"></script>
    <script src="{{ asset('assets/libs/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <script src="{{ asset('dist/js/pages/dashboards/dashboard1.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/prism/prism.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/forms/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/jquery.repeater/repeater-init.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/jquery.repeater/dff.js') }}"></script>
</html>