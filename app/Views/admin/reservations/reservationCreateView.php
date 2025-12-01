<?php

use App\Helpers\ViewHelper;

$page_title = 'Create a Reservation';
ViewHelper::loadAdminHeader($page_title);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="row justify-content-left">
        <div class="col-xl-8">
            <form action="<?= APP_ADMIN_URL ?>/reservations/store" method="POST" enctype="multipart/form-data">
                <div class="d-grid gap-3" style="margin: 50px 0;">
                    <h1><?= $page_title ?></h1>
                    <?= App\Helpers\FlashMessage::render() ?>

                    <div class="row g-2"> <!-- First row container -->
                        <div class="col-md">
                            <!-- First Name input -->
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInputGrid" name="first_name">
                                <label for="floatingInputGrid">First Name</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <!-- Last Name input -->
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInputGrid" name="last_name">
                                <label for="floatingInputGrid">Last Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2"> <!-- Second row container -->
                        <div class="col-md">
                            <!-- Email input -->
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInputGrid" name="email">
                                <label for="floatingInputGrid">User Email</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <!-- Phone input -->
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInputGrid" name="phone">
                                <label for="floatingInputGrid">Phone Number</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <!-- Reservation Type input -->
                            <label for="reservation_type">Reservation Type</label>
                            <select name="reservation_type" id="reservation_type" class="form-select">
                                <option value="hourly">Hourly</option>
                                <option value="trip">Trip</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2"> <!-- Third row container -->
                        <div class="col-md">
                            <!-- Start Time input -->
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="floatingInputGrid" name="start_time" value="">
                                <label for="floatingInputGrid">Start Time</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <!-- Pickup input -->
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInputGrid" name="pickup">
                                <label for="floatingInputGrid">Pickup Location</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2"> <!-- Fourth row container -->
                        <div class="col-md">
                            <!-- End Time input -->
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="floatingInputGrid" name="end_time">
                                <label for="floatingInputGrid">End Time</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <!-- Dropoff input -->
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInputGrid" name="dropoff">
                                <label for="floatingInputGrid">Dropoff Location</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" name="comments"></textarea>
                        <label for="floatingTextarea2">Comments</label>
                    </div>

                    <div class="button-container" style="text-align: right;">
                        <a href="<?= APP_ADMIN_URL ?>/reservations" class="btn btn-danger">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
        </div>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
