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
                <td>Dropoff</td>
                <td>Comments</td>
                <td>Reservation Type</td>
                <td>Reservation Status</td>
                <td>Created at</td>
                <td>Updated at</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $key => $reservation): ?>
                <tr>
                    <td> <?= $reservation['reservation_id'] ?> </td>
                    <td><?= $reservation['email'] ?></td>
                    <td><?= $reservation['start_time'] ?></td>
                    <td><?= $reservation['end_time'] ?></td>
                    <td><?= $reservation['pickup'] ?></td>
                    <td><?= $reservation['dropoff'] ?></td>
                    <td><?= $reservation['comments'] ?></td>
                    <td><?= $reservation['reservation_type'] ?></td>
                    <td><?= $reservation['reservation_status'] ?></td>
                    <td><?= $reservation['created_at'] ?></td>
                    <td><?= $reservation['updated_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>


<?php

ViewHelper::loadJsScripts();
ViewHelper::loadAdminFooter();
?>