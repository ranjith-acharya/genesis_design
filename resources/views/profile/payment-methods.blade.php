@extends('layouts.profile')

@section('title', 'Payment Methods')

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
                // M.toast({
                //     html: "There was a network error initializing stripe. Please try again.",
                //     classes: "imperial-red"
                // });
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
                    } else {
                        window.location.reload();
                    }
                }).catch(err => {
                    toastr.error('There was a network error initializing stripe. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                    // M.toast({
                    //     html: "There was a network error initializing stripe. Please try again.",
                    //     classes: "imperial-red"
                    // });
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
        <div class="col s12 center">
            <h4 class="prussian-blue-text">Your Payment Methods</h4>
        </div>
    </div>
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
        <h5 class="prussian-blue-text">Add New</h5>
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
@endsection
