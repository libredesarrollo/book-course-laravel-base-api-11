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

    <input id="card-holder-name" type="text">

    <div id="card-element"></div>

    <button id="card-button">
        Update Payment Method
    </button>

    <script>
        const stripe = Stripe("{{ config('cashier.key') }}")

        const elements = stripe.elements()
        const cardElement = elements.create('card')

        cardElement.mount("#card-element")

        // process card
        const cardHolderName = document.getElementById('card-holder-name')
        const cardButton = document.getElementById('card-button')

        cardButton.addEventListener('click', async (e) => {
            const {
                setupIntent,
                error
            } = await stripe.confirmCardSetup(
                "{{ $intent->client_secret }}", {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                }
            )

            if (error) {
                // error
                console.error(error)
            } else{
                // success
                console.log(setupIntent)
            }

        })
    </script>

</body>

</html>
