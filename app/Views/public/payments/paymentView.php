<?php

use App\Helpers\FlashMessage;
use App\Helpers\ViewHelper;
use Square\Environments;
use Square\SquareClient;

$page_title = "Payment";

ViewHelper::loadCustomerHeader($page_title);

?>

<div id="wrapper">

    <?= FlashMessage::render(); ?>
    <div id=card>

    </div>
    <form id="payment-form">
        <div id="card-container"></div>
        <button id="card-button" type="button">Pay $1.00</button>
    </form>
    <div id="payment-status-container"></div>
    <!-- <button id="pay">Pay</button> -->
</div>
<script>
    const appId = 'sandbox-sq0idb-EpHytLaLRq1mo5DN9R6xaw';
    const locationId = 'L5F899PJSJCEZ';

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

    async function initializeCard(payments) {
        const card = await payments.card();
        await card.attach('#card-container');

        return card;
    }

    async function CreatePayment(token) {
        const body = JSON.stringify({
            locationId,
            sourceId: token,
            idempotencyKey: window.crypto.randomUUID(),
        });

        //todo set route for payment
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
                cardButton.disabled = true;
                const token = await tokenize(card);
                const paymentResults = await CreatePayment(token);

                if (paymentResults.payment?.status === 'COMPLETED') {
                    displayPaymentResults('SUCCESS');
                    console.debug('Payment Success', paymentResults);

                    const redirectUrl =
                        paymentResults.redirect_to || '/luxury-taxi/reservations';
                    <?php
                    FlashMessage::success("Payment Successful");
                    ?>
                } else {
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

        const cardButton = document.getElementById('card-button');
        cardButton.addEventListener('click', async function(event) {
            await handlePaymentMethodSubmission(event, card);
        });

    });
</script>



<?php

ViewHelper::loadCustomerFooter();

?>
