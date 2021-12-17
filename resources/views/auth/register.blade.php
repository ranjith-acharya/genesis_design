<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <title>Design Genesis - Register</title>
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
            <div class="auth-box">
                <div id="loginform">
                    <div class="logo">
                        <span class="db"><img src="{{ asset('assets/images/logo-icon.png') }}" alt="logo" /></span>
                        <h5 class="font-medium m-b-20">Sign up to continue <br> Design Genesis</h5>
                    </div>
                    <!-- Form -->
                    <div class="row">
                    <form id="register_form" class="col s12" method="POST"> 
                        @csrf
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="first_name" type="text" class="validate @error('first_name') invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first name">
                                    <label for="first_name">First Name</label>
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                                <div class="input-field col s6">
                                    <input id="last_name" type="text" class="validate @error('last_name') invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last name">
                                    <label for="last_name">Last Name</label>
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="company" type="text" class="validate @error('company') invalid @enderror" name="company" value="{{ old('company') }}" required autocomplete="company">
                                    <label for="company">Company Name</label>
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                                <div class="input-field col s6">
                                    <input id="phone" type="text" class="validate @error('phone') invalid @enderror" name="phone" value="{{ old('phone') }}" maxlength="10" required autocomplete="phone">
                                    <label for="phone">Phone</label>
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                            </div>
                            <!-- email -->
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="email" type="email" class="validate @error('email') invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    <label for="email">Email</label>
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                            </div>
                            <!-- pwd -->
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="password" type="password" class="validate @error('password') invalid @enderror" name="password" required autocomplete="new-password">
                                    <label for="password">Password</label>
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                                <div class="input-field col s6">
                                    <input id="password-confirm" type="password" class="validate" name="password_confirmation" required autocomplete="new-password">
                                    <label for="password-confirm">Confirm Password</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <label for="card-element">Card</label>
                                    <div id="card-element"></div>
                                    <span class="helper-text red-text bold" id="stripe_error"></span>
                                </div>
                            </div>
                            <!-- pwd -->
                            <div class="row m-t-5">
                                <div class="col s12">
                                    <label>
                                        <input type="checkbox" class="validate" name="terms" id="terms"/>
                                        <span>Agree to all Terms</span>
                                        <span class="helper-text red-text bold errors"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- pwd -->
                            <div class="row m-t-40">
                                <div class="col s12">
                                    <button class="btn-large w100 red" type="button" id="register">Sign Up</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="center-align m-t-20 db">
                        Already have an account? <a href="{{ route('login') }}">Login!</a>
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
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        let stripe;
        let elements;
        let cardElement;
        let jsonData = {};
        document.addEventListener('DOMContentLoaded', function () {
            stripe = Stripe('pk_test_j6ygL4Z0meZGJwZ16QT8nR0i00vuQ0k3NE');
            elements = stripe.elements();
            cardElement = elements.create('card', {
                iconStyle: 'solid',
                style: {
                    base: {
                        iconColor: '#8898AA',
                        lineHeight: '36px',
                        fontWeight: 300,
                        fontSize: '19px',
                        '::placeholder': {
                            color: '#8898AA',
                        },
                    },
                    invalid: {
                        iconColor: '#E63946',
                        color: '#E63946',
                    }
                },
                classes: {
                    focus: 'is-focused',
                    empty: 'is-empty',
                },
            });
            cardElement.mount('#card-element');
        });

        function toggleForm(what) {
            let form = document.forms["register_form"].getElementsByTagName("input");
            for (let item of form) {
                item.disabled = (what === "disabled")
            }
        }

        // validate user info
        document.getElementById('register').addEventListener('click', function () {

            document.getElementById('register').disabled = true;
            let form = document.forms["register_form"].getElementsByTagName("input");
            jsonData = {};

            toggleForm("disabled");
            for (let item of form) {
                if (item.getAttribute("name"))
                    jsonData[item.getAttribute("name")] = (item.type === 'checkbox') ? item.checked : item.value;

            }

            fetch("{{ route('register.verify') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                },
                body: JSON.stringify(jsonData)
            }).then(async response => {
                return {json: await response.json(), "status": response.status};
            }).then(response => {
                console.log(response)
                if (response.json.failed) {
                    for (let jsonKey in response.json.errors) {
                        document.getElementById(jsonKey).parentNode.querySelectorAll('span.errors')[0].innerText = response.json.errors[jsonKey];
                    }
                    document.getElementById('register').disabled = false;
                    toggleForm("");
                } else {
                    processStripe(response.json.stripe);
                }
            }).catch(err => {
                M.toast({
                    html: "There was a network error validation inputs. Please try again.",
                    classes: "imperial-red"
                });
                //console.error(err);
                document.getElementById('register').disabled = false;
                toggleForm("");
            });
        });

        function processStripe(data) {
            stripe.confirmCardSetup(
                data.key,
                {
                    payment_method: {
                        card: cardElement
                    }
                },
            ).then(function (result) {
                console.log(result)
                if (result.error) {
                    document.getElementById('stripe_error').innerText = result.error.message;
                    document.getElementById('register').disabled = false;
                    toggleForm("");
                } else {
                    createUser(data.customer, result);
                }
            });
        }

        function createUser(customer_id, result) {
            jsonData['stripe_id'] = customer_id
            jsonData['payment_id'] = result.setupIntent.payment_method
            fetch("{{ route('register') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                },
                body: JSON.stringify(jsonData)
            }).then(async response => {
                return {json: await response.json(), "status": response.status};
            }).then(response => {
                console.log(response)
                if (response.status !== 201) {
                    M.toast({
                        html: "There was an unknown error creating user. Please try again.",
                        classes: "imperial-red"
                    });
                } else {
                    window.location = "{{ route('home') }}"
                }
            }).catch(err => {
                M.toast({
                    html: "There was a network error creating user. Please try again.",
                    classes: "imperial-red"
                });
                console.error(err);
                document.getElementById('register').disabled = false;
                toggleForm("");
            });
        }

    </script>
</body>
</html>