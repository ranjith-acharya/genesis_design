@extends('layouts.app')

@section('title', "Register")

@section('js')
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        let stripe;
        let elements;
        let cardElement;
        let jsonData = {};
        document.addEventListener('DOMContentLoaded', function () {
            stripe = Stripe('{{env('STRIPE_KEY')}}');
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
                console.error(err);
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
                    window.location = "{{ route('login') }}"
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
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col s12 m10 offset-m1">
                <div class="card">
                    <div class="center">
                        <h4 class="card-header imperial-red-text" style="padding-top: 20px;text-transform: capitalize">Register For An Account</h4>
                    </div>
                    <div class="card-content">
                        <form id="register_form">
                            <div class="center">
                                <span class="helper-text red-text">All fields are required</span>
                            </div>
                            <div class="input-field row">
                                <div class="col s12 m6">
                                    <label for="first_name">First Name</label>
                                    <input id="first_name" type="text" class="form-control @error('first_name') invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first name">
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                                <div class="col s12 m6">
                                    <label for="last_name">Last Name</label>
                                    <input id="last_name" type="text" class="form-control @error('last_name') invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last name">
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                            </div>
                            <div class="input-field row">
                                <div class="col s12 m6">
                                    <label for="company">Company</label>
                                    <input id="company" type="text" class="form-control @error('company') invalid @enderror" name="company" value="{{ old('company') }}" required autocomplete="company">
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                                <div class="col s12 m6">
                                    <label for="phone">Phone</label>
                                    <input id="phone" type="text" class="form-control @error('phone') invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                            </div>
                            <div class="input-field row">
                                <div class="col s12">
                                    <label for="email">{{ __('E-Mail Address') }}</label>
                                    <input id="email" type="email" class="form-control @error('email') invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                            </div>
                            <div class="input-field row">
                                <div class="col s12 m6">
                                    <label for="password">{{ __('Password') }}</label>
                                    <input id="password" type="password" class="form-control @error('password') invalid @enderror" name="password" required autocomplete="new-password">
                                    <span class="helper-text red-text bold errors"></span>
                                </div>
                                <div class="col s12 m6">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m8 offset-m2">
                                    <label for="card-element">Card</label>
                                    <div id="card-element"></div>
                                    <span class="helper-text red-text bold" id="stripe_error"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <label class="center-block" style="display: block">Terms and Conditions</label>
                                    <div style="height: 250px;overflow: auto">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nulla malesuada pellentesque elit eget gravida cum
                                        sociis. Urna et pharetra pharetra massa massa ultricies mi. Mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Dictum fusce ut placerat orci. Praesent semper feugiat
                                        nibh sed. Suspendisse ultrices gravida dictum fusce ut placerat orci nulla pellentesque. Ut consequat semper viverra nam libero justo. Mauris pellentesque pulvinar pellentesque
                                        habitant morbi tristique senectus et netus. Mauris a diam maecenas sed enim ut sem viverra. Quisque egestas diam in arcu cursus euismod quis. Facilisi etiam dignissim diam quis
                                        enim lobortis scelerisque fermentum. Mattis vulputate enim nulla aliquet porttitor lacus luctus. Lectus sit amet est placerat in egestas erat. Lorem sed risus ultricies tristique
                                        nulla aliquet enim. Tincidunt vitae semper quis lectus nulla at.

                                        Et egestas quis ipsum suspendisse ultrices gravida dictum fusce. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Ac turpis egestas maecenas pharetra. Ultricies
                                        integer quis auctor elit sed. Tellus rutrum tellus pellentesque eu tincidunt tortor aliquam. Magnis dis parturient montes nascetur ridiculus mus mauris vitae. Eget nunc lobortis
                                        mattis aliquam faucibus purus in massa. Est ante in nibh mauris cursus mattis. Tristique sollicitudin nibh sit amet commodo nulla. Bibendum ut tristique et egestas quis ipsum.
                                        Accumsan in nisl nisi scelerisque. Nisi quis eleifend quam adipiscing vitae proin sagittis nisl rhoncus. Euismod quis viverra nibh cras pulvinar. Mattis nunc sed blandit libero
                                        volutpat sed cras ornare. Risus sed vulputate odio ut enim blandit volutpat maecenas. Ipsum suspendisse ultrices gravida dictum fusce ut. Venenatis urna cursus eget nunc
                                        scelerisque viverra. Viverra accumsan in nisl nisi.

                                        Tortor at risus viverra adipiscing. In massa tempor nec feugiat nisl pretium fusce id. Mattis nunc sed blandit libero. Et molestie ac feugiat sed lectus vestibulum mattis
                                        ullamcorper velit. Facilisi nullam vehicula ipsum a arcu cursus. Ligula ullamcorper malesuada proin libero nunc consequat. Ac turpis egestas maecenas pharetra convallis posuere.
                                        Ultricies tristique nulla aliquet enim tortor at auctor urna. Diam quis enim lobortis scelerisque fermentum dui faucibus in ornare. Vel risus commodo viverra maecenas accumsan
                                        lacus vel. Rutrum tellus pellentesque eu tincidunt tortor aliquam. Auctor augue mauris augue neque gravida in fermentum et. Ac auctor augue mauris augue neque gravida in.

                                        Adipiscing elit ut aliquam purus sit amet luctus venenatis. Natoque penatibus et magnis dis. Justo eget magna fermentum iaculis eu. Consectetur a erat nam at lectus urna duis
                                        convallis. Interdum consectetur libero id faucibus. Volutpat consequat mauris nunc congue nisi. Proin sagittis nisl rhoncus mattis rhoncus urna. Iaculis eu non diam phasellus
                                        vestibulum lorem. Fames ac turpis egestas sed tempus urna. Elit pellentesque habitant morbi tristique senectus et.

                                        Massa ultricies mi quis hendrerit dolor. Nisi quis eleifend quam adipiscing vitae proin sagittis. Sit amet purus gravida quis blandit turpis cursus. Vitae tortor condimentum
                                        lacinia quis vel eros donec ac. Facilisi morbi tempus iaculis urna id volutpat lacus. Eu mi bibendum neque egestas congue. Arcu cursus euismod quis viverra nibh cras. Amet commodo
                                        nulla facilisi nullam vehicula ipsum a arcu cursus. Facilisi cras fermentum odio eu feugiat. Amet porttitor eget dolor morbi non arcu risus quis varius
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <label>
                                        <input type="checkbox" name="terms" id="terms" required/>
                                        <span>I've read and agree with the above terms and conditions</span>
                                        <span class="helper-text red-text bold errors"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field row mb-0 center">
                                <div class="col-md-6 offset-md-4">
                                    <button type="button" class="btn steel-blue-outline-button" id="register">{{ __('Register') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
