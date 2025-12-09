<?php

use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use App\Helpers\ViewHelper;

$page_title = "View Reservations";
ViewHelper::loadCustomerHeader($page_title, 'find-reservation');

$reservations = $data['reservations'] ?? [];
$cars = $data['cars'] ?? [];

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
    <div class="hero-and-form">
        <section class="hero-section">
            <div class="hero-content">
                <h1>Find your Reservation</h1>
                <p>Enter your booking details below to quickly find and manage your reservation.</p>
            </div>
        </section>
        <div class="page-content">
            <div class="search-container">
                <form action="reservations" method="post" class="reservation-search-form">
                    <div class="search-inputs">
                        <div class="input-wrapper">
                            <label for="email" class="search-label">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" class="search-input" required>
                        </div>
                        <div class="input-wrapper">
                            <label for="reservation_id" class="search-label">Confirmation Number</label>
                            <input type="text" id="reservation_id" name="reservation_id" placeholder="Enter confirmation number" class="search-input" required>
                        </div>
                    </div>
                    <button action="submit" class="all-cars-link">Search</button>
                </form>
            </div>
        </div>
    </div>

<?php
} else { ?>
    <!-- Customer hero section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>View Reservations</h1>
            <p>Easily and efficiently manage all your bookings.</p>
        </div>
    </section>
<?php } ?>

<!-- Reservation list (shared for guest and customer) -->
<?php if (!empty($reservations)) { ?>
    <div class="page-content">
        <?= FlashMessage::render() ?>
        <div class="container my-5">
            <?php foreach ($reservations as $reservation): ?>
                <?php
                $data = [
                    'reservations' => $reservation,
                    'cars' => $cars
                ];
                include __DIR__ . '/reservationCard.php';
                ?>
                <br>
            <?php endforeach; ?>
        </div>
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
            button.style.background = "#1c4014";
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
