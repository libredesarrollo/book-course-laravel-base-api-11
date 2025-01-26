<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>

    <div id="card-element"></div>

    <button id="confirm-payment">
        Payment
    </button>

    <script>
        const stripe = Stripe("{{ config('cashier.key') }}")

        const elements = stripe.elements()
        const cardElement = elements.create('card')
        cardElement.mount("#card-element")

        const confirmPaymentButton = document.getElementById('confirm-payment')

        confirmPaymentButton.addEventListener('click', async (e) => {
            const {
                paymentIntent,
                error
            } = await stripe.confirmCardPayment(
                "{{ $clientSecret }}", {
                    payment_method: {
                        card: cardElement,
                    }
                }
            )

            if (error) {
                console.error('Error al confirmar el pago:', error);
            } else {
                console.log('Pago confirmado:', paymentIntent);
            }

        })
    </script>

</body>

</html>
