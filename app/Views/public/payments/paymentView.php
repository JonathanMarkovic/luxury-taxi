<?php

use App\Helpers\FlashMessage;
use App\Helpers\ViewHelper;
use Square\Environments;
use Square\SquareClient;

$page_title = "Payment";

ViewHelper::loadCustomerHeader($page_title);

?>
<!-- Written referencing https://developer.squareup.com/docs/build-basics/access-tokens#use-an-access-token-in-your-code -->


<div id="wrapper">

    <?= FlashMessage::render(); ?>
    <div id=card>

    </div>
    <form id="payment-form">
        <!-- This is the actual credit card field -->
        <div id="card-container"></div>
        <button id="card-button" type="button">Pay </button>
    </form>
    <div id="payment-status-container"></div>
</div>
<script>
    //* These should be moved to a consts or env for production
    const appId = 'sandbox-sq0idb-EpHytLaLRq1mo5DN9R6xaw';
    const locationId = 'L5F899PJSJCEZ';

    //* The customer details, this is a default Square example customer
    // todo: include fields to collect this information from the customer or extract it from the reservation information
    const verificationDetails = {
        amount: '1.00',
        billingContact: {
            givenName: 'John',
            familyName: 'Doe',
            email: 'john.doe@square.example',
            phone: '3214563987',
            addressLines: ['123 Main Street', 'Apartment 1'],
            city: 'Oakland',
            state: 'CA',
            countryCode: 'US',
        },
        currencyCode: 'USD',
        intent: 'CHARGE',
        customerInitiated: true,
        sellerKeyedIn: false,
    };

    //* This creates the card text input and puts it in the div section above
    async function initializeCard(payments) {
        const card = await payments.card();
        await card.attach('#card-container');

        return card;
    }

    //* This calls the PaymentController::pay method
    async function CreatePayment(token) {
        const body = JSON.stringify({
            locationId,
            sourceId: token,
            idempotencyKey: window.crypto.randomUUID(),
        });

        const paymentResponse = await fetch('payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body,
        });

        if (paymentResponse.ok) {
            return paymentResponse.json();
        }

        const errorBody = await paymentResponse.text();
        throw new Error(errorBody);
    }

    //* This is the token to
    async function tokenize(card) {
        const tokenResult = await card.tokenize(verificationDetails);
        if (tokenResult.status === 'OK') {
            return tokenResult.token;
        } else {
            let errorMessage = `Tokenization failed-status: ${tokenResult.status}`;
            if (tokenResult.errors) {
                errorMessage += ` and errors: ${JSON.stringify(
              tokenResult.errors
            )}`;
            }
            throw new Error(errorMessage);
        }
    }

    // status is either SUCCESS or FAILURE;
    function displayPaymentResults(status) {
        const statusContainer = document.getElementById(
            'payment-status-container'
        );
        if (status === 'SUCCESS') {
            statusContainer.classList.remove('is-failure');
            statusContainer.classList.add('is-success');
        } else {
            statusContainer.classList.remove('is-success');
            statusContainer.classList.add('is-failure');
        }

        statusContainer.style.visibility = 'visible';
    }

    //* This is the start point for the javascript
    document.addEventListener('DOMContentLoaded', async function() {
        if (!window.Square) {
            throw new Error('Square.js failed to load properly');
        }

        let payments;
        try {
            payments = window.Square.payments(appId, locationId);
        } catch {
            const statusContainer = document.getElementById(
                'payment-status-container',
            );
            statusContainer.className = 'missing-credentials';
            statusContainer.style.visibility = 'visible';
            return;
        }

        let card;
        try {
            card = await initializeCard(payments);
        } catch (e) {
            console.error('Initializing Card failed', e);
            return;
        }


        async function handlePaymentMethodSubmission(event, card) {
            event.preventDefault();

            try {
                //* This disables the pay button to prevent a double click(double submission)
                cardButton.disabled = true;
                const token = await tokenize(card);
                const paymentResults = await CreatePayment(token);

                //* Check payment status
                //* Set flash messages

                if (paymentResults.payment?.status === 'COMPLETED') {
                    displayPaymentResults('SUCCESS');
                    console.debug('Payment Success', paymentResults);

                    // const redirectUrl =
                    //     paymentResults.redirect_to || '/luxury-taxi/reservations';
                    <?php
                    FlashMessage::success("Payment Successful");
                    ?>
                    const redirectUrl =
                        paymentResults.redirect_to || '/luxury-taxi/reservations';
                } else {
                    //* If payment fails re-enable the payment button so the user can try again
                    displayPaymentResults('FAILURE');
                    cardButton.disabled = false;
                    <?php
                    FlashMessage::error("Payment unsuccessful");
                    ?>
                }
            } catch (e) {
                cardButton.disabled = false;
                displayPaymentResults('FAILURE');
                console.error(e.message);
            }
        }

        //* Payment button functionality
        const cardButton = document.getElementById('card-button');
        cardButton.addEventListener('click', async function(event) {
            await handlePaymentMethodSubmission(event, card);
        });

    });
</script>

<?php

ViewHelper::loadCustomerFooter();

?>
