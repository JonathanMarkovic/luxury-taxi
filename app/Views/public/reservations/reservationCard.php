<?php

use App\Helpers\SessionManager;
?>

<div class="reservationBox p-4"
    style="background:#111; border:1px solid #333; border-radius:10px; color:white;">

    <div class="row">

        <!-- Car image -->
        <div class="col-md-3 d-flex align-items-start justify-content-center">
            <img src="/uploads/images/car20.jpg"
                style="width:100%; max-width:220px; border-radius:6px; border:2px solid #a6814c;">
        </div>

        <!-- Customer Details -->
        <div class="col-md-3">
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
                    }  elseif ($reservation['reservation_status'] == "completed") {
                        echo " class='completed-reservation-banner'";
                    }  elseif ($reservation['reservation_status'] == "denied") {
                        echo " class='denied-reservation-banner'";
                    }
                ?>
            >
                <?= $reservation['reservation_status'] ?>
            </div>
            </p>
        </div>

        <!-- Reservation Details & Edit Form -->
        <div class="col-md-4">
            <form action="<?= APP_USER_URL ?>/reservations/edit/<?= $reservation['reservation_id'] ?>" method="post">
                <fieldset id="fieldset_<?= $reservation['reservation_id'] ?>" <?= SessionManager::get('modify_mode') == false ? " disabled" : "" ?>>
                    <!-- Pickup input -->
                    <div class="form-floating">
                        <input type="text" class="form-control" id="pickup" name="pickup" value="<?= $reservation['pickup'] ?>" required>
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
                        <div class="col-md-12">
                            <label for="reservation_type" class="form-label">Reservation Type</label>
                            <div class="form-floating">
                                <select name="reservation_type" id="reservation_type" class="form-select">
                                    <option value="hourly" <?= $reservation['reservation_type'] == 'hourly' ? 'selected' : '' ?>>Hourly</option>
                                    <option value="trip" <?= $reservation['reservation_type'] == 'trip' ? 'selected' : '' ?>>Trip</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Price -->
                    <br>
                    <div class="static-reservation-banner">
                        Price
                        <br>
                        <?= $reservation['total_amount'] == null ? "not set" : "$ " . $reservation['total_amount'] ?>
                    </div>
                    <br>
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
        <div class="col-md-2 d-flex flex-column justify-content-start gap-2">
            <?php if (!in_array($reservation['reservation_status'], ['completed', 'denied', 'cancelled'])) { ?>
                <!-- Cancel Button -->
                <button class="btn btn-outline-light"
                    style="background:#db5050; border: #db5050; color: white;">
                    <a class="nav-link" href="<?= APP_USER_URL ?>/reservations/cancel/<?= $reservation['reservation_id'] ?>">
                        Cancel Reservation
                    </a>
                </button>

                <!-- Modify Button -->
                <button class="btn toggle-btn"
                    data-mode="modify"
                    style="background:#555; color:white;"
                    onclick="toggleEdit(this)">
                    Modify
                </button>
            <?php
            }
            ?>





            <?php if ($reservation['reservation_status'] === 'approved' && $reservation['payment_status'] !== "paid") { ?>
                <button class="btn" style="background:#3347fb; color:white;">
                    <a class="nav-link" href="<?= APP_BASE_URL ?>/payment/<?= $reservation['reservation_id'] ?>">
                        Pay
                    </a>
                </button>
            <?php } ?>
        </div>

    </div>

</div>
<br>
