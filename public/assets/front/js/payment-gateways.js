// Handle form submission
var form = document.getElementById('payment');
form.addEventListener('submit', function (event) {
    event.preventDefault();
    const slectedPaymentGateway = $('#payment-gateway').val();
    if (slectedPaymentGateway == null){
        $("#payement_error").text('Payment Method Is Required !');
        document.getElementById('confirmBtn').innerHTML = 'Place Order';
        document.getElementById('confirmBtn').disabled = false;
        return false;
    }
    if (slectedPaymentGateway == 'Stripe') {
        stripe.createToken(cardElement).then(function (result) {
            if (result.error) {
                // Display errors to the customer
                var errorElement = document.getElementById('stripe-errors');
                errorElement.textContent = result.error.message;
                //button replace
                document.getElementById('confirmBtn').innerHTML = 'Place Order';
                document.getElementById('confirmBtn').disabled = false;
            }else {
                // Send the token to your server
                stripeTokenHandler(result.token);
            }
        });
    } else if (slectedPaymentGateway == 'Authorize.net') {
        sendPaymentDataToAnet();
    } else {
        $('#payment').submit();
    }
});
