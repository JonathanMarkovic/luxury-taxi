<?php

use App\Helpers\FlashMessage;
use App\Helpers\ViewHelper;
use Square\Environments;
use Square\SquareClient;

$page_title = "Payment";

$balance = $data['balance'];
$reservation_id = $data['reservation_id'];

ViewHelper::loadCustomerHeader($page_title);

?>
<!-- Written referencing https://developer.squareup.com/docs/build-basics/access-tokens#use-an-access-token-in-your-code -->


<div id="wrapper">

    <?= FlashMessage::render(); ?>
    <div id=card>

    </div>
    <form id="payment-form">
        <!-- //TODO style this form better -->
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="email">Email</label>
        <input type="text" id="email" name="email" required>

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" required>

        <label for="address_line_1">Address Line 1</label>
        <input type="text" id="address_line_1" name="address_line_1" required>

        <label for="address_line_2">Address Line 2</label>
        <input type="text" name="address_line_2" id="address_line_2">

        <label for="city">City</label>
        <input type="text" id="city" name="city">

        <label for="province">Province</label>
        <input type="text" id="province" name="province">

        <label for="country_code">Country Code (ex: CA)</label>
        <input type="text" id="country_code" name="country_code">

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
    // const verificationDetails = getVerificationDetails();
    // {
    // amount: '1.00',
    // billingContact: {
    //     givenName: 'John',
    //     familyName: 'Doe',
    //     email: 'john.doe@square.example',
    //     phone: '3214563987',
    //     addressLines: ['123 Main Street', 'Apartment 1'],
    //     city: 'Oakland',
    //     state: 'CA',
    //     countryCode: 'US',
    // },
    // currencyCode: 'USD',
    // intent: 'CHARGE',
    // customerInitiated: true,
    // sellerKeyedIn: false,

    //* Getting the values from the form now instead of the hard coded test from Square
    /**
     * Undocumented function
     * Loads all the content from the form into verification details needed for Square
     * @return void
     */
    function getVerificationDetails() {
        return {
            amount: "<?php echo $balance ?>",
            billingContact: {
                givenName: document.getElementById('first_name').value,
                familyName: document.getElementById('last_name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                addressLines: [
                    document.getElementById('address_line_1').value,
                    document.getElementById('address_line_2').value
                ],
                city: document.getElementById('city').value,
                state: document.getElementById('province').value,
                countryCode: document.getElementById('country_code').value,
            },
            currencyCode: 'CAD',
            intent: 'CHARGE',
            customerInitiated: true,
            sellerKeyedIn: false,
        }
    }

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

        const paymentResponse = await fetch('/luxury-taxi/payment/<?php echo $reservation_id ?>', {
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
        //* Load all content from form
        const verificationDetails = getVerificationDetails();

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

                //
                if (!paymentResults.payment || paymentResults.payment.status !== 'COMPLETED') {
                    displayPaymentResults('FAILURE');
                    cardButton.disabled = false;
                    return;
                }

                displayPaymentResults('SUCCESS');
                window.location.href = paymentResults.redirect_to;
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
