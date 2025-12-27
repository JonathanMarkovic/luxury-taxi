<?php

use App\Helpers\SessionManager;
use App\Helpers\FlashMessage;

$reservation = $data['reservations'];
$cars = $data['cars'];
// echo $reservation['reservation_id'];
$reservation_id = $reservation['reservation_id'];
?>

<div class="reservationBox p-4"
    style="background:#2a2a2a; border:1px solid #333; border-radius:10px; color:white;">

    <div class="row">
        <!-- Customer Details -->
        <div class="col-md-4">
            <p><strong><?= hs(trans('reservationCard.first_name')) ?>: </strong><?= $reservation['first_name'] ?></p>
            <p><strong><?= hs(trans('reservationCard.last_name')) ?>: </strong><?= $reservation['last_name'] ?></p>
            <p><strong><?= hs(trans('reservationCard.email')) ?>: </strong><?= $reservation['email'] ?></p>
            <p><strong><?= hs(trans('reservationCard.phone')) ?>: </strong><?= $reservation['phone'] ?></p>
            <p><strong><?= hs(trans('reservationCard.id')) ?>: </strong><?= $reservation['reservation_id'] ?></p>
            <p>
    <strong><?= hs(trans('reservationCard.reservation_status')) ?>: </strong>
    <div
        <?php
        $statusClass = strtolower($reservation['reservation_status']);
        echo " class='{$statusClass}-reservation-banner'";
        ?>>
        <?= hs(trans('reservationCard.status.' . strtolower($reservation['reservation_status']))) ?>
    </div>
</p>
        </div>

        <!-- Reservation Details & Edit Form -->
        <div class="col-md-5">
            <form action="<?= APP_USER_URL ?>/reservations/edit/<?= $reservation_id ?>" method="post" id="reservationDetails">
                <fieldset id="fieldset_<?= $reservation_id ?>" disabled>
                    <!-- Pickup input -->
                    <div class="form-floating">
                        <input type="text" class="form-control" id="pickup" name="pickup" value="<?= $reservation['pickup'] ?>" required>
                        <?php //dd($reservation['pickup']);
                        ?>
                        <label><?= hs(trans('reservationCard.pickup')) ?></label>
                    </div>
                    <br>
                    <!-- Dropoff input -->
                    <div class="form-floating">
                        <input type="text" class="form-control" id="Start" name="dropoff" value="<?= $reservation['dropoff'] ?>" required>
                        <label><?= hs(trans('reservationCard.dropoff')) ?></label>
                    </div>
                    <br>
                    <div class="row g-3 mb-3">
                        <!-- Start time input -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="<?= $reservation['start_time'] ?>">
                                <label for="start_time"><?= hs(trans('reservationCard.start_time')) ?></label>
                            </div>
                        </div>
                        <!-- End time input -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="<?= $reservation['end_time'] ?>">
                                <label for="end_time"><?= hs(trans('reservationCard.end_time')) ?></label>
                            </div>
                        </div>
                    </div>
                    <!-- Reservation type input -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-12 position-relative">
                            <select name="reservation_type" id="reservation_type" class="form-select custom-floating-select">
                                <option value="" disabled selected hidden>Select a <?= hs(trans('reservationCard.reservation_type')) ?></option>
                                <option value="hourly" <?= ($reservation['reservation_type'] ?? '') === 'hourly' ? 'selected' : '' ?>><?= hs(trans('reservationCard.hourly')) ?></option>
                                <option value="trip" <?= ($reservation['reservation_type'] ?? '') === 'trip' ? 'selected' : '' ?>><?= hs(trans('reservationCard.trip')) ?></option>
                            </select>
                            <label for="reservation_type" class="floating-label"><?= hs(trans('reservationCard.reservation_type')) ?></label>
                        </div>
                    </div>
                    <!-- Car input -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-12 position-relative">
                            <select name="cars_id" id="cars_id" class="form-select custom-floating-select">
                                <option value="" disabled selected hidden><?= hs(trans('reservationCard.selectVehicle')) ?></option>
                                <!-- Loop through all cars -->
                                <?php foreach ($cars as $car): ?>
                                    <option
                                        value="<?= $car['cars_id'] ?>"
                                        <?= (isset($reservation['cars_id']) && $reservation['cars_id'] == $car['cars_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="cars_id" class="floating-label"><?= hs(trans('reservationCard.vehicle')) ?></label>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="static-reservation-banner">
                        <?= hs(trans('reservationCard.price')) ?>
                        <br>
                        <?php // dd($reservation);
                        ?>
                        <?= $reservation['price'] == null ? hs(trans('reservationCard.not_set')): "$ " . $reservation['price'] ?>
                    </div>
                    <!-- Payment -->
                       <div class="static-reservation-banner">
    <?= hs(trans('reservationCard.payment_status')) ?>
    <br>
    <?php
    $paymentStatusKey = strtolower($reservation['payment_status'] ?? 'pending');
    echo hs(trans('reservationCard.payment_status_values.' . $paymentStatusKey));
    ?>
</div>
                </fieldset>
            </form>
        </div>

        <!-- Buttons -->
        <div class="col-md-3 d-flex flex-column justify-content-start gap-2">
            <?php if (!in_array($reservation['reservation_status'], ['completed', 'denied', 'cancelled'])) { ?>
                <!-- Cancel Button -->
                <button class="btn"
                    style="background:#471C1C; border: #471C1C; color: white;" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $reservation['reservation_id'] ?>">
                    <?= hs(trans('reservationCard.cancel_reservation')) ?>
                </button>

                <!-- Modify Button -->
                <button class="btn toggle-btn"
                    data-mode="modify"
                    style="background:#555; color:white;"
                    onclick="toggleEdit(this)">
                    <?= hs(trans('reservationCard.modify_reservation')) ?>
                </button>
            <?php
            }
            ?>

            <?php if ($reservation['reservation_status'] === 'approved' && $reservation['payment_status'] !== "paid") { ?>
                <!-- <button class="btn" style="background:#a6814c; color:white;">
                    <a class="nav-link" href="<?= APP_BASE_URL ?>/payment/<?= $reservation['reservation_id'] ?>">
                        <?= hs(trans('reservationCard.pay')) ?>
                    </a>
                </button> -->
            <?php } ?>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade custom-modal" id="deleteModal<?= $reservation['reservation_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel<?= $reservation['reservation_id'] ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel<?= $reservation['reservation_id'] ?>"><?= hs(trans('reservationCard.confirm_cancellation')) ?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                <p class="mb-2"><?= hs(trans('reservationCard.cancellation_warning')) ?></p>
                <small style="color: #888;"><?= hs(trans('reservationCard.cancellation_note')) ?></small>
            </div>
                    <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style=" background-color: #444 !important; color: white !important;"><?= hs(trans('reservationCard.no')) ?></button>
                <a href="<?= APP_USER_URL ?>/reservations/cancel/<?= $reservation['reservation_id'] ?>" class="btn btn-secondary"><?= hs(trans('reservationCard.yes')) ?></a>
            </div>
                </div>
            </div>
        </div>

    </div>

</div>
<br>
<script>
    const reservationTranslations = {
        modify: "<?= hs(trans('reservations.modify')) ?>",
        save: "<?= hs(trans('reservations.save')) ?>"
    };
</script>
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
            button.innerText = reservationTranslations.save;
            button.style.background = "#1c4014";
            button.setAttribute("data-mode", "save");

        } else if (mode === "save"){
            // Submit form if there are changes and switch back to Modify button
            form.submit();

            // After form submits, page reloads so this won't run.
            // But in case you want it without reload:
            /*
            fieldset.disabled = true;
            button.innerText = "Modify";
            button.style.background = "#555";
            button.setAttribute("data-mode", "modify");*/
        }
    }
</script>
