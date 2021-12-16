@push('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            M.Modal.init(document.querySelectorAll('.modal'));
        });

        document.getElementById('quote_price').addEventListener('click', function () {
            let modal = M.Modal.getInstance(document.getElementById('cr_quote_modal'));
            modal.open();
        });

        function sendCrQuote(overload = 0) {
            console.log(overload);
            let quote = document.getElementById('change_request_quote')
            let note = document.getElementById('modal_note');
            if (parseInt(quote.value) > 0 || (parseInt(quote.value) >= 0 && overload === 1)) {
                axios("{{route('engineer.change_requests.quote')}}", {
                    method: 'post',
                    data: {
                        quote: quote.value,
                        design: '{{$design->id}}',
                        note: note.value,
                        approve: true,
                        change_request_id: '{{$design->proposals[0]->changeRequest->id}}'
                    },
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                    }
                }).then(response => {
                    if (response.status === 200 || response.status === 201) {
                        M.toast({
                            html: "Quote sent!",
                            classes: "steel-blue"
                        });
                        window.location.reload();
                    } else {
                        M.toast({
                            html: "There was a error sending the quote. Please try again.",
                            classes: "imperial-red"
                        });
                    }

                }).catch(err => {
                    M.toast({
                        html: "There was a network error. Please try again.",
                        classes: "imperial-red"
                    });
                });
            } else if (parseInt(quote.value) === 0) {
                M.toast({
                    html: "You're giving this quote out for free!&nbsp;&nbsp;<button class=\"btn-flat toast-action\" onclick='sendCrQuote(1)' style='cursor: pointer'>Click here to confirm</button>",
                }, {
                    displayLength: 9000
                });
            } else
                M.toast({
                    html: "Make sure quote is greater than or equal to 0",
                    classes: "imperial-red"
                });
        }

        function reject() {

            let note = document.getElementById('modal_note');
            if (note.value.trim()){
                axios("{{route('engineer.change_requests.quote')}}", {
                    method: 'post',
                    data: {
                        quote: 0,
                        design: '{{$design->id}}',
                        note: note.value,
                        approve: false,
                        change_request_id: '{{$design->proposals[0]->changeRequest->id}}'
                    },
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                    }
                }).then(response => {
                    if (response.status === 200 || response.status === 201) {
                        M.toast({
                            html: "Quote sent!",
                            classes: "steel-blue"
                        });
                        window.location.reload();
                    } else {
                        M.toast({
                            html: "There was a error sending the quote. Please try again.",
                            classes: "imperial-red"
                        });
                    }

                }).catch(err => {
                    M.toast({
                        html: "There was a network error. Please try again.",
                        classes: "imperial-red"
                    });
                });
            }
            else
                M.toast({
                    html: "You must type in a note to reject a change request",
                    classes: "imperial-red"
                });
        }
    </script>
@endpush

<div id="cr_quote_modal" class="modal">
    <div class="modal-content">
        <h4>Quote Change Request</h4>
        <div class="row">
            <div class="input-field col s12">
                <input id="change_request_quote" type="number" placeholder="Enter price here" value="{{$design->type->latestPrice->change_price}}">
                <label for="change_request_quote">Quote</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <textarea id="modal_note" class="materialize-textarea"></textarea>
                <label for="modal_note">Note</label>
            </div>
        </div>
        <p class="imperial-red-text" style="font-size: 1.5em">The default price for a change request for this type of design is ${{$design->type->latestPrice->change_price}}</p>
    </div>
    <div class="modal-footer">
        <a class="modal-close waves-effect waves-green btn-flat left">Cancel</a>
        <a class="waves-effect waves-green btn-flat mr-m" id="send-with-file" onclick="reject()">Reject Change Request</a>
        <a class="waves-effect waves-green btn-flat" id="send-with-file" onclick="sendCrQuote()">Send Quote</a>
    </div>
</div>
