<?php

use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use App\Helpers\ViewHelper;

$page_title = "View Reservations";
ViewHelper::loadCustomerHeader($page_title);

$reservations = $data['reservations'] ?? [];

// Get session values
$user_role = SessionManager::get('user_role');
$is_auth = SessionManager::get('is_authenticated');

// Determine mode
$is_guest = ($user_role === 'guest' || $user_role === null || !$is_auth);
$is_customer = ($is_auth === true && $user_role !== 'guest');
?>

<?php
// SessionManager::destroy();
// dd(SessionManager::get('user_role'));
if (SessionManager::get('user_role') === 'guest' || SessionManager::get('user_role') === null) {
?>
    <!-- Guest hero section -->
    <section class="hero-section1">
        <div class="hero-content">
            <h1>Find your Reservation</h1>
            <p>Enter your booking details below to quickly find and manage your reservation.</p>
        </div>
    </section>

    <center>
        <div class="page-content">
            <form action="reservations" method="post">
                <label for="email">Email</label>
                <input type="text" id="email" name="email">
                <label for="reservation_id">Reservation Number</label>
                <input type="text" id="reservation_id" name="reservation_id">
                <button action="submit" class="all-cars-link">Search</button>
            </form>
        </div>
    </center>


<?php
} else { ?>
    <!-- Customer hero section -->
    <section class="hero-section1">
        <div class="hero-content">
            <h1>All Reservations</h1>
            <p>Easily and efficiently view and manage all your bookings.</p>
        </div>
    </section>
<?php } ?>

<?= FlashMessage::render() ?>
<!-- Reservation list (shared for guest and customer) -->
<?php if (!empty($reservations)) { ?>
    <div class="container my-5">
        <?php foreach ($reservations as $reservation): ?>
            <?php include __DIR__ . '/reservationCard.php'; ?>
            <br>
        <?php endforeach; ?>
    </div>
<?php } ?>

<script>
    function toggleEdit(button) {
        const box = button.closest('.reservationBox');
        const fieldset = box.querySelector('fieldset');
        const form = box.querySelector('form');

        let mode = button.getAttribute("data-mode");

        if (mode === "modify") {
            // Switch to edit mode with Save button
            fieldset.disabled = false;
            button.innerText = "Save";
            button.style.background = "#4ac654";
            button.setAttribute("data-mode", "save");

        } else {
            // Submit form if there are changes and switch back to Modify button
            form.submit();

            // After form submits, page reloads so this won't run.
            // But in case you want it without reload:
            fieldset.disabled = true;
            button.innerText = "Modify";
            button.style.background = "#555";
            button.setAttribute("data-mode", "modify");
        }
    }
</script>



<?php
ViewHelper::loadCustomerFooter();
?>
