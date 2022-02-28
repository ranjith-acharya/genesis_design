async function holdPayment(design_name = "none", overload = null) {
    let stripe = Stripe(stripePublicKey);

    return await axios(paymentHoldUrl, {
        method: 'post',
        data: (overload !== null) ? overload : {name: design_name},
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
        }
    }).then(response => {
        console.log("Payment 345656 : ",response);
        if (response.status === 200 || response.status === 201) {
            if (response.data.client_secret === "skip") {
                return "skip";
            } else {
                return stripe.confirmCardPayment(response.data.client_secret)
                    .then(function (result) {
                        return result;
                    });
            }
        } else {
            M.toast({
                html: "There was a error processing payment. Please try again.",
                classes: "imperial-red"
            });
            return false;
        }
    }).catch(err => {
        M.toast({
            html: "There was a network error. Please try again.",
            classes: "imperial-red"
        });
        console.error(err);
        return false;
    });
}
