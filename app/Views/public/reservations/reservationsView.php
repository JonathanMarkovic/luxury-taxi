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
                <h1><?= hs(trans('reservations.guestHeroTitle')) ?></h1>
                <p><?= hs(trans('reservations.guestHeroDesc')) ?></p>
            </div>

        </section>
         <?= FlashMessage::render() ?>
         <br>
        <div class="page-content">
            <form action="reservations" method="post" class="d-flex flex-column align-items-center gap-3">
                <div class="mb-3">
                    <input type="text" id="email" name="email" placeholder=<?= hs(trans('reservations.emailPlaceholder')) ?> class="find-reservation-input">
                </div>
                <div class="mb-3">
                    <input type="text" id="reservation_id" name="reservation_id" placeholder=<?= hs(trans('reservations.confirmationPlaceholder')) ?> class="find-reservation-input">
                </div>
                <button action="submit" class="all-cars-link"><?= hs(trans('reservations.search')) ?></button>
            </form>
        </div>
    </div>

<?php
} else { ?>
    <!-- Customer hero section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1><?= hs(trans('reservations.customerHeroTitle')) ?></h1>
            <p><?= hs(trans('reservations.customerHeroDesc')) ?></p>
        </div>
    </section>
<?php } ?>

<!-- Reservation list (shared for guest and customer) -->
<?php if (!empty($reservations)) { ?>
    <div class="page-content">

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
        // const form = document.getElementById('reservationDetails');
        const form = box.querySelector('form');

        let mode = button.getAttribute("data-mode");

        if (mode === "modify") {
            // Switch to edit mode with Save button
            fieldset.disabled = false;
            button.innerText = <?= hs(trans('reservations.save')) ?>;
            button.style.background = "#1c4014";
            button.setAttribute("data-mode", "save");

        } else {
            // Submit form if there are changes and switch back to Modify button
            form.submit();

            // After form submits, page reloads so this won't run.
            // But in case you want it without reload:
            fieldset.disabled = true;
            button.innerText = <?= hs(trans('reservations.modify')) ?>;
            button.style.background = "#555";
            button.setAttribute("data-mode", "modify");
        }
    }
</script>



<?php
ViewHelper::loadCustomerFooter();
?>
