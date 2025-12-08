<?php
//TODO:
use App\Domain\Models\ReservationModel;
use App\Helpers\SessionManager;
use App\Helpers\ViewHelper;

$page_title = "reservations";
ViewHelper::loadCustomerHeader($page_title);
$reservations = $data['reservations'] ?? [];
// dd($reservations);

if (SessionManager::get('is_authenticated')) {
?>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td>Reservation id</td>
                <td>Start Time</td>
                <td>End Time</td>
                <td>Pickup</td>
                <td>Dropoff</td>
                <td>Comments</td>
                <td>Reservation Type</td>
                <td>Reservation Status</td>
                <td>Price</td>
                <td>Total Paid</td>
                <td>Created at</td>
                <td>Updated at</td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $key => $reservation): ?>
                <tr class="<?= $highlightClass ?>">
                    <td> <?= $reservation['reservation_id'] ?> </td>
                    <td><?= $reservation['start_time'] ?></td>
                    <td><?= $reservation['end_time'] ?></td>
                    <td><?= $reservation['pickup'] ?></td>
                    <td><?= $reservation['dropoff'] ?></td>
                    <td><?= $reservation['comments'] ?></td>
                    <td><?= $reservation['reservation_type'] ?></td>
                    <td>
                        <?php
                        if ($reservation['reservation_status'] == 'approved') {
                            echo '<span class="badge bg-success">' . ucfirst($reservation['reservation_status']) . '</span>';
                        } elseif ($reservation['reservation_status'] == 'denied') {
                            echo '<span class="badge bg-secondary">' . ucfirst($reservation['reservation_status']) . '</span>';
                        } elseif ($reservation['reservation_status'] == 'cancelled') {
                            echo '<span class="badge bg-danger">' . ucfirst($reservation['reservation_status']) . '</span>';
                        } elseif ($reservation['reservation_status'] == 'completed') {
                            echo '<span class="badge bg-primary">' . ucfirst($reservation['reservation_status']) . '</span>';
                        } elseif ($reservation['reservation_status'] == 'refunded') {
                            echo '<span class="badge bg-dark">' . ucfirst($reservation['reservation_status']) . '</span>';
                        } else {
                            echo '<span class="badge bg-warning">' . ucfirst($reservation['reservation_status']) . '</span>';
                        }
                        ?>
                    </td>
                    <td>$<?= $reservation['price'] ?></td>
                    <td>$<?= $reservation['total_paid'] ?></td>
                    <td><?= $reservation['created_at'] ?></td>
                    <td><?= $reservation['updated_at'] ?></td>
                    <td>
                        <!-- THIS NEEDS TO BE CHANGED -->
                        <a class="btn btn-success" href="<?= APP_USER_URL ?>/reservations/view/<?= $reservation['reservation_id'] ?>"> View</a>
                    </td>

                </tr>
            <?php endforeach; ?>

        <?php
    }
    ?>
