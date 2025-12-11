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
            <p><strong>First Name: </strong><?= $reservation['first_name'] ?></p>
            <p><strong>Last Name: </strong><?= $reservation['last_name'] ?></p>
            <p><strong>Email: </strong><?= $reservation['email'] ?></p>
            <p><strong>Phone: </strong><?= $reservation['phone'] ?></p>
            <p>
                <strong>Reservation Status: </strong>
            <div
                <?php
                if ($reservation['reservation_status'] == "pending") {
                    echo " class='pending-reservation-banner'";
                } elseif ($reservation['reservation_status'] == "approved") {
                    echo " class='approved-reservation-banner'";
                } elseif ($reservation['reservation_status'] == "cancelled") {
                    echo " class='cancelled-reservation-banner'";
                } elseif ($reservation['reservation_status'] == "completed") {
                    echo " class='completed-reservation-banner'";
                } elseif ($reservation['reservation_status'] == "denied") {
                    echo " class='denied-reservation-banner'";
                }
                ?>>
                <?= $reservation['reservation_status'] ?>
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
                        <label>Pickup</label>
                    </div>
                    <br>
                    <!-- Dropoff input -->
                    <div class="form-floating">
                        <input type="text" class="form-control" id="Start" name="dropoff" value="<?= $reservation['dropoff'] ?>" required>
                        <label>Drop-off</label>
                    </div>
                    <br>
                    <div class="row g-3 mb-3">
                        <!-- Start time input -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="<?= $reservation['start_time'] ?>">
                                <label for="start_time">Start Time</label>
                            </div>
                        </div>
                        <!-- End time input -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="<?= $reservation['end_time'] ?>">
                                <label for="end_time">End Time</label>
                            </div>
                        </div>
                    </div>
                    <!-- Reservation type input -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-12 position-relative">
                            <select name="reservation_type" id="reservation_type" class="form-select custom-floating-select">
                                <option value="" disabled selected hidden>Select a Reservation Type</option>
                                <option value="hourly" <?= ($reservation['reservation_type'] ?? '') === 'hourly' ? 'selected' : '' ?>>Hourly</option>
                                <option value="trip" <?= ($reservation['reservation_type'] ?? '') === 'trip' ? 'selected' : '' ?>>Trip</option>
                            </select>
                            <label for="reservation_type" class="floating-label">Reservation Type</label>
                        </div>
                    </div>
                    <!-- Car input -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-12 position-relative">
                            <select name="cars_id" id="cars_id" class="form-select custom-floating-select">
                                <option value="" disabled selected hidden>Select a Vehicle</option>
                                <!-- Loop through all cars -->
                                <?php foreach ($cars as $car): ?>
                                    <option
                                        value="<?= $car['cars_id'] ?>"
                                        <?= (isset($reservation['cars_id']) && $reservation['cars_id'] == $car['cars_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="cars_id" class="floating-label">Vehicle</label>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="static-reservation-banner">
                        Price
                        <br>
                        <?php // dd($reservation);
                        ?>
                        <?= $reservation['price'] == null ? "not set" : "$ " . $reservation['price'] ?>
                    </div>
                    <!-- Payment -->
                    <div class="static-reservation-banner">
                        Payment Status
                        <br>
                        <?= $reservation['payment_status'] == null ? "pending" : $reservation['payment_status'] ?>
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
                    Cancel Reservation
                </button>

                <!-- Modify Button -->
                <button class="btn toggle-btn"
                    data-mode="modify"
                    style="background:#555; color:white;"
                    onclick="toggleEdit(this)">
                    Modify Reservation
                </button>
            <?php
            }
            ?>

            <?php if ($reservation['reservation_status'] === 'approved' && $reservation['payment_status'] !== "paid") { ?>
                <button class="btn" style="background:#294087; color:white;">
                    <a class="nav-link" href="<?= APP_BASE_URL ?>/payment/<?= $reservation['reservation_id'] ?>">
                        Pay
                    </a>
                </button>
            <?php } ?>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal<?= $reservation['reservation_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel<?= $reservation['reservation_id'] ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel<?= $reservation['reservation_id'] ?>">Confirm Cancellation</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to cancel this reservation?
                        <br><small class="text-muted">This cannot be undone.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                        <a href="<?= APP_USER_URL ?>/reservations/cancel/<?= $reservation['reservation_id'] ?>" class="btn btn-primary">Yes</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<br>
