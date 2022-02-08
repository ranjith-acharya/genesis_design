@extends('layouts.profile')

@section('css')
    <link rel="stylesheet" href="{{asset('font-awesome/css/brands.min.css')}}">
    <style>
        .collection .collection-item.avatar > i {
            position: absolute;
            width: 50px;
            height: 42px;
            overflow: hidden;
            left: 15px;
            display: inline-block;
            vertical-align: middle;
        }

        .collection .collection-item.avatar i {
            font-size: 40px;
            line-height: 42px;
            color: #fff;
            background-color: #999;
            text-align: center;
        }

        .collection .collection-item.avatar {
            min-height: 60px;
            padding-left: 78px;
        }
    </style>
@endsection

@section('js')
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        let stripe;
        let elements;
        let cardElement;
        const defaultLoader = document.getElementById('loading_default');
        const newLoader = document.getElementById('loading_new');

        document.addEventListener('DOMContentLoaded', function () {
            stripe = Stripe("{{env('STRIPE_KEY')}}");
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

        document.getElementById('addCard').addEventListener('click', function (elem) {
            newLoader.style.display = 'block';
            elem.disabled = true;
            fetch("{{ route('payment.new') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                }
            }).then(async response => {
                return {json: await response.json(), "status": response.status};
            }).then(response => {
                stripe.confirmCardSetup(
                    response.json.secret,
                    {
                        payment_method: {
                            card: cardElement
                        }
                    },
                ).then(function (result) {
                    if (result.error) {
                        document.getElementById('stripe_error').innerText = result.error.message;
                        elem.disabled = false;
                        newLoader.style.display = 'none';
                    } else {
                        window.location.reload();
                    }
                });
            }).catch(err => {
                toastr.error('There was a network error initializing stripe. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                newLoader.style.display = 'none';
                console.error(err);
                elem.disabled = false;
            });

        });

        document.getElementById('card-collection').addEventListener('click', function (elem) {
            const target = elem.target;
            if (target.classList.contains('make-default')) {
                defaultLoader.style.display = 'block';
                fetch("{{ route('payment.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                    },
                    body: JSON.stringify({
                        "id": target.getAttribute('data-id')
                    })
                }).then(async response => {
                    return {json: await response.json(), "status": response.status};
                }).then(response => {
                    if (response.status !== 200) {
                        defaultLoader.style.display = 'none';
                        toastr.error('There was an error updating your payment method. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                        // M.toast({
                        //     html: "There was an error updating your payment method. Please try again.",
                        //     classes: "imperial-red"
                        // });
                    } else {
                        window.location.reload();
                    }
                }).catch(err => {
                    toastr.error('There was a network error initializing stripe. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                    defaultLoader.style.display = 'none';
                    console.error(err);
                    elem.disabled = false;
                });
            }
        })
    </script>
@endsection

@section('profile_body')
<div class="row">
    <div class="col s4">
        <div class="card">
            @php
                $profile_img=Auth::user()->role.".png";
            @endphp
            @if ($message = Session::get('success'))
                <script>
                    toastr.success('{{$message}}', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                </script>
	        @endif
            <div class="card-content white-text social-profile d-flex justify-content-center">
                <div class="align-self-center">
                    <img src="{{ asset('assets/images/users/'.$profile_img) }}" class="" width="100">
                    <h4 class="card-title white-text capitalize">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                    <h6 class="card-subtitle white-text">{{ Auth::user()->email }}</h6>
                    <h6 class="card-subtitle"><span class="label label-red white-text capitalize">{{ Auth::user()->role }}</span></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col s8">
        <div class="card">
            <div class="row">
                <div class="col s12">
                    <ul class="tabs">
                        <li class="tab col s3"><a class="active" href="#profile">Profile</a></li>
                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
                            <li class="tab col s3"><a href="#paymentMethods">Payment Methods</a></li>
                        @endif
                    </ul>
                </div>
                <div id="profile" class="col s12">
                    <div class="card-content">
                        <div class="row">
                            <div class="col s12"> <b>Full Name</b>
                                <br>
                                <p class="capitalize">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            </div>
                            <div class="col s12  b-t p-t-20"> <b>Mobile</b>
                                <br>
                                <p>(+91) {{ Auth::user()->phone }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 b-t p-t-20"> <b>Email</b>
                                <br>
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                            @if(Auth::user()->company == "")

                            @else
                            <div class="col s12 b-t p-t-20"> <b>Company</b>
                                <br>
                                <p class="capitalize">{{ Auth::user()->company }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class=" col s12 b-t p-t-20">
                                <a class="modal-trigger" href="#editProfile"><button type="button" class="btn btn-small green white-text">Edit</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
                <div id="paymentMethods" class="col s12">
                    <div class="card-content">
                        <div class="row">
                            <div class="col s12">
                            <h5 class="prussian-blue-text">Current Default</h5>
                                <ul class="collection">
                                    @component('components.card-item', ['card' => $default, "showButton" => false])@endcomponent
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <h5 class="prussian-blue-text">Other Saved Methods</h5>
                                <div id="loading_default" style="display: none">
                                    <div class="progress">
                                        <div class="indeterminate"></div>
                                    </div>
                                </div>
                                    <ul class="collection" id="card-collection">
                                        @if (sizeof($cards) > 0)
                                            @foreach($cards as $card)
                                                @component('components.card-item', ['card' => $card, "showButton" => true])@endcomponent
                                            @endforeach
                                        @else
                                            @component('components.card-item', ['card' => null, "showButton" => false])@endcomponent
                                        @endif
                                    </ul>
                            </div>
                        </div>
                        <div class="row">
                            <h5 class="prussian-blue-text" style="margin-left:10px;">Add New</h5>
                            <div id="loading_new" style="display: none">
                                <div class="progress">
                                    <div class="indeterminate"></div>
                                </div>
                            </div>
                            <div class="col s12 m8 offset-m2 center-align">
                                <label for="card-element"></label>
                                <div id="card-element"></div>
                                <div class="helper-text red-text bold" id="stripe_error"></div>
                                <button type="button" class="btn mt-s imperial-red-outline-button" id="addCard">Add new card</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div id="editProfile" class="modal">
    <form method="post" action="@if(Auth::user()->role == 'admin') {{ route('profile.admin.update', Auth::user()->id) }} @elseif(Auth::user()->role == 'manager') {{ route('profile.manager.update', Auth::user()->id) }} @elseif(Auth::user()->role == 'engineer') {{ route('profile.engineer.update', Auth::user()->id) }} @else {{ route('profile.customer.update', Auth::user()->id) }} @endif">
    @csrf
    @method('PUT')
        <div class="modal-content">
            <h4>Update Profile</h4><br>
            <div class="row">
                <div class="col s6">
                    <div class="input-field w100 col">
                        <input id="first_name" type="text" class="validate @error('first_name') invalid @enderror" name="first_name" value="{{ Auth::user()->first_name }}" required autocomplete="first name">
                        <label for="first_name">First Name</label>
                        @error('first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col s6">
                    <div class="input-field w100 col">
                        <input id="last_name" type="text" class="validate @error('last_name') invalid @enderror" name="last_name" value="{{ Auth::user()->last_name }}" required autocomplete="last name">
                        <label for="last_name">Last Name</label>
                        @error('last_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s6">
                    <div class="input-field w100 col">
                        <input id="company" type="text" class="" name="company" value="{{ Auth::user()->company }}" autocomplete="company">
                        <label for="company">Company Name</label>
                    </div>
                </div>
                <div class="col s6">
                    <div class="input-field w100 col">
                        <input id="phone" type="text" class="validate @error('phone') invalid @enderror" name="phone" value="{{ Auth::user()->phone }}" required maxlength="10" autocomplete="phone">
                        <label for="phone">Phone Number</label>
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s6">
                    <div class="input-field w100 col">
                        <input id="email" type="text" class="validate @error('email') invalid @enderror" name="email" value="{{ Auth::user()->email }}" required autocomplete="email" disabled>
                        <label for="email">Email Address</label>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-small green white-text">Update</button>
        </div>
    </form>
  </div>
@endsection