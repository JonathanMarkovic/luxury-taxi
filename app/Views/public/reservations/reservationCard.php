<?php
use App\Helpers\ViewHelper;
use App\Helpers\SessionManager;
use App\Helpers\FlashMessage;
$page_title = "View Reservations";
ViewHelper::loadCustomerHeader($page_title, 'reservation');
$reservation = $data['reservations'] ?? [];
$cars = $data['cars'] ?? [];

?>

<div class="reservation-box" style="background-color: #1a1a1a">
    <div class="row align-items-start">
        <!-- Customer Details -->
        <div class="col-md-3">
            <h6 class="mb-3" style="color: #aaa; font-weight: 600;">Customer Details</h6>
            <p class="mb-2"><strong style="color: #888;">Name:</strong> <?= $reservation['first_name'] ?> <?= $reservation['last_name'] ?></p>
            <p class="mb-2"><strong style="color: #888;">Email:</strong> <?= $reservation['email'] ?></p>
            <p class="mb-2"><strong style="color: #888;">Phone:</strong> <?= $reservation['phone'] ?></p>
            <p class="mb-0">
                <strong style="color: #888;">Status:</strong>
                <span class="status-text status-<?= $reservation['reservation_status'] ?>">
                    <?= ucfirst($reservation['reservation_status']) ?>
                </span>
            </p>
        </div>

        <!-- Reservation Details & Edit Form -->
        <div class="col-md-6">
            <h6 class="mb-3" style="color: #aaa; font-weight: 600;">Reservation Details</h6>
            <form id="reservationForm<?= $reservation['reservation_id'] ?>" action="<?= APP_USER_URL ?>/reservations/update/<?= $reservation['reservation_id'] ?>" method="post">
                <fieldset id="fieldset_<?= $reservation['reservation_id'] ?>" <?= SessionManager::get('modify_mode') == false ? " disabled" : "" ?>>
                    <!-- Pickup input -->
                    <div class="info-box mb-3">
                        <div class="info-box-label">Pickup</div>
                        <input type="text" class="form-control border-0 bg-transparent p-0 info-box-value" id="pickup" name="pickup" value="<?= $reservation['pickup'] ?>" required>
                    </div>

                    <!-- Dropoff input -->
                    <div class="info-box mb-3">
                        <div class="info-box-label">Drop-off</div>
                        <input type="text" class="form-control border-0 bg-transparent p-0 info-box-value" id="dropoff" name="dropoff" value="<?= $reservation['dropoff'] ?>" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <!-- Start time input -->
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-box-label">Start Time</div>
                                <input type="datetime-local" class="form-control border-0 bg-transparent p-0 info-box-value" id="start_time" name="start_time" value="<?= $reservation['start_time'] ?>">
                            </div>
                        </div>
                        <!-- End time input -->
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-box-label">End Time</div>
                                <input type="datetime-local" class="form-control border-0 bg-transparent p-0 info-box-value" id="end_time" name="end_time" value="<?= $reservation['end_time'] ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Reservation type input -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6 position-relative">
                            <select name="reservation_type" id="reservation_type" class="form-select custom-floating-select" style=" border: 1px solid #333 !important;">
                                <option value="" disabled selected hidden>Select Type</option>
                                <option value="hourly" <?= ($reservation['reservation_type'] ?? '') === 'hourly' ? 'selected' : '' ?>>Hourly</option>
                                <option value="trip" <?= ($reservation['reservation_type'] ?? '') === 'trip' ? 'selected' : '' ?>>Trip</option>
                            </select>
                            <label for="reservation_type" class="floating-label">Reservation Type</label>
                        </div>

                        <!-- Car input -->
                        <div class="col-md-6 position-relative">
                            <select name="cars_id" id="cars_id" class="form-select custom-floating-select" style=" border: 1px solid #333 !important;">
                                <option value="" disabled selected hidden>Select Vehicle</option>
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

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-box-label">Price</div>
                                <div class="info-box-value">
                                    <?= $reservation['total_amount'] == null ? "Not set" : "$" . number_format($reservation['total_amount'], 2) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-box-label">Payment Status</div>
                                <div class="info-box-value">
                                    <?= ucfirst($reservation['payment_status'] ?? 'pending') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

        <!-- Buttons -->
        <div class="col-md-3">
            <h6 class="mb-3" style="font-weight: 600;">Actions</h6>
            <div class="d-flex flex-column gap-2">
                <?php if (!in_array($reservation['reservation_status'], ['completed', 'denied', 'cancelled'])) { ?>
                    <!-- Modify Button -->
                    <button class="btn btn-action btn-modify"
                        data-bs-toggle="modal"
                        data-bs-target="#updateModal<?= $reservation['reservation_id'] ?>">
                        Modify Reservation
                    </button>

                    <!-- Cancel Button -->
                    <button class="btn btn-action btn-cancel"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal<?= $reservation['reservation_id'] ?>">
                        Cancel Reservation
                    </button>
                <?php } ?>

                <?php if ($reservation['reservation_status'] === 'approved' && $reservation['payment_status'] !== "paid") { ?>
                    <a href="<?= APP_USER_URL ?>/payment/<?= $reservation['reservation_id'] ?>"
                       class="btn btn-action btn-pay">
                        Pay Now
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Update Confirmation Modal -->
<div class="modal fade custom-modal" id="updateModal<?= $reservation['reservation_id'] ?>" ...>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <p class="mb-2">Are you sure you want to update this reservation?</p>
                <small style="color: #888;">The changes will be saved and the admin will have to review them again.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style=" background-color: #444 !important; color: white !important;">No, Cancel</button>
                 <button type="submit" form="reservationForm<?=$reservation['reservation_id'] ?>" name="update" class="btn btn-secondary">Yes, Update</a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade custom-modal" id="deleteModal<?= $reservation['reservation_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel<?= $reservation['reservation_id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel<?= $reservation['reservation_id'] ?>">Confirm Cancellation</h5>

            </div>
            <div class="modal-body">
                <p class="mb-2">Are you sure you want to cancel this reservation?</p>
                <small style="color: #888;">This action cannot be undone.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style=" background-color: #444 !important; color: white !important;">No, Keep It</button>
                <a href="<?= APP_USER_URL ?>/reservations/cancel/<?= $reservation['reservation_id'] ?>" class="btn btn-secondary">Yes, Cancel</a>
            </div>
        </div>
    </div>
</div>
