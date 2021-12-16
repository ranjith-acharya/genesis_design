@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{asset('js/designs/payment.js')}}"></script>
    <script type="text/javascript">
        // Payment stuff
        const paymentHoldUrl = '{{route('change_requests.hold')}}';
        const stripePublicKey = '{{env('STRIPE_KEY')}}';

        document.getElementById('accept_quote').addEventListener('click', function (elem) {
            elem.target.disabled = true;
            document.getElementById('stripe_card').style.display = 'none'

            holdPayment(null, {
                "design_id": "{{$design->id}}",
                "change_request_id": "{{$design->proposals[0]->changeRequest->id}}"
            }).then(resp => {
                console.log(resp)
                if (resp) {
                    if (resp.error) {
                        document.getElementById('stripe_error').innerText = resp.error.message;
                        elem.disabled = false;
                        document.getElementById('stripe_card').style.display = 'block'
                    } else {

                        fetch("{{route('change_requests.accept')}}", {
                            method: 'post',
                            body: JSON.stringify({
                                "stripe_payment_code": (resp === 'skip') ? "skipped" : resp.paymentIntent.id,
                                "design_id": "{{$design->id}}",
                                "change_request_id": "{{$design->proposals[0]->changeRequest->id}}"
                            }),
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                            }
                        }).then(async response => {
                            return {db_response: await response.json(), "status": response.status};
                        }).then(response => {
                            if (response.status === 200 || response.status === 201) {
                                console.log(response.db_response)
                                window.location = '{{route('design.view', $design->id)}}';
                            } else {
                                M.toast({
                                    html: "There was a error accepting the change request. Please try again.",
                                    classes: "imperial-red"
                                });
                                console.error(response);
                                elem.disabled = false;
                            }
                        }).catch(err => {
                            M.toast({
                                html: "There was a network error. Please try again.",
                                classes: "imperial-red"
                            });
                            console.error(err);
                            elem.disabled = false;
                        });
                    }
                } else {
                    console.log("error")
                    elem.disabled = false;
                }
            })
        })
    </script>
@endpush
