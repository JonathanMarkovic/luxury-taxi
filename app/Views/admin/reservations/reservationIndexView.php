<?php

use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'List of Reservations';
ViewHelper::loadAdminHeader($page_title);
$reservations = $data['reservations'];
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="mb-4">
        <?= App\Helpers\FlashMessage::render() ?>
    </div>
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2><?= $page_title ?></h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div>
                <!--ADD this to route and redirect to reservationCreateView.php -->
                <a class="btn btn-primary" href="<?= APP_ADMIN_URL ?>/reservations/create">+ New Reservation</a>
            </div>
        </div>
    </div>
    <!-- Table of Reservations should go here -->
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td>Reservation id</td>
                <td>User Email</td>
                <td>Start Time</td>
                <td>End Time</td>
                <td>Pickup</td>
                <td>Drop-off</td>
                <td>Vehicle</td>
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
                <?php
                // if its cancelled and the price was paid, highlight it
                $highlightClass = '';
                if (
                    ($reservation['reservation_status'] == 'cancelled' && isset($reservation['total_paid']) && $reservation['total_paid'] !== null) ||
                    (isset($reservation['total_paid']) && isset($reservation['price']) && $reservation['total_paid'] > $reservation['price']) || ($reservation['reservation_status'] == 'pending' && isset($reservation['total_paid']) && isset($reservation['price']) && $reservation['total_paid'] == $reservation['price']) || ($reservation['reservation_status'] == 'pending' && isset($reservation['price']))
                ) {
                    $highlightClass = 'table-warning';
                }
                ?>
                <tr class="<?= $highlightClass ?>">
                    <td> <?= $reservation['reservation_id'] ?> </td>
                    <td><?= $reservation['email'] ?></td>
                    <td><?= $reservation['start_time'] ?></td>
                    <td><?= $reservation['end_time'] ?></td>
                    <td><?= $reservation['pickup'] ?></td>
                    <td><?= $reservation['dropoff'] ?></td>
                    <td><?= $reservation['brand'] ?> <?= $reservation['model'] ?> <?= $reservation['year'] ?></td>
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
                        <a class="btn btn-success" href="<?= APP_ADMIN_URL ?>/reservations/view/<?= $reservation['reservation_id'] ?>"> View</a>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>


<?php

ViewHelper::loadJsScripts();
ViewHelper::loadAdminFooter();
?>
