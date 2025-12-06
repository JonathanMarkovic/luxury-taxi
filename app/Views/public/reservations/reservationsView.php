<?php

use App\Helpers\SessionManager;
use App\Helpers\ViewHelper;

$page_title = "View Reservation";
ViewHelper::loadCustomerHeader($page_title);

$reservations = $data['reservations'] ?? [];

?>
<?php
if (SessionManager::get('is_authenticated')) {
?>
    <?php foreach ($reservations as $key => $reservation): ?>
        <div class="container my-5">
            <h2 class="text-center mb-4" style="color:#a6814c;">Reservation Details</h2>

            <div class="reservationBox p-4"
                style="background:#111; border:1px solid #333; border-radius:10px; color:white;">

                <div class="row">


                    <div class="col-md-3 d-flex align-items-start justify-content-center">
                        <img src="/uploads/images/car20.jpg"
                            style="width:100%; max-width:220px; border-radius:6px; border:2px solid #a6814c;">
                    </div>

                    <div class="col-md-3">
                        <!-- SET VALUES DYNAMICALLY HERE -->
                        <p><strong>First Name:</strong> John</p>
                        <p><strong>Last Name:</strong> Doe</p>
                        <p><strong>Email:</strong> fahad@hotmail.com</p>
                        <p><strong>Phone:</strong> 514-157-4567</p>
                    </div>


                    <div class="col-md-4">
                        <p><strong>Pickup:</strong> 1253 Rue Decarie</p>
                        <p><strong>Drop-off:</strong> Saint Catherine St</p>

                        <p><strong>Date:</strong> 03/15/2026</p>
                        <p><strong>Start:</strong> 2:00 PM</p>
                        <p><strong>End:</strong> 7:00 PM</p>

                        <p><strong>Occasion:</strong> Hourly</p>
                        <p><strong>Price:</strong> $458.50</p>
                    </div>


                    <div class="col-md-2 d-flex flex-column justify-content-start gap-2">

                        <button class="btn btn-outline-light"
                            style="border-color:#a6814c; color:#a6814c;">
                            Cancel
                        </button>

                        <button class="btn"
                            style="background:#555; color:white;">
                            Modify
                        </button>
                        <?php
                        if ($reservation['reservation_status'] === 'approved') {
                        ?>
                            <button class="btn"
                                style="background:#0d6efd; color:white;">
                                <a class="nav-link" href="<?= APP_BASE_URL ?>/payment/<?= $reservation['reservation_id'] ?>">Pay</a>
                            </button>
                        <?php } ?>
                    </div>

                </div>

            </div>
        </div>
    <?php endforeach; ?>
<?php } else {


?>
    <!-- Put guest find reservation form here -->
<?php
}
?>

<?php
ViewHelper::loadCustomerFooter();
?>
