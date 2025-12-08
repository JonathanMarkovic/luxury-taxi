<?php

use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'Home';
ViewHelper::loadCustomerHeader($page_title, 'home');
$cars = $data['cars'];
$threeCars = $data['threeCars'];
// dd($cars);

$first_name = SessionManager::get('first_name') ?? '';
$last_name = SessionManager::get('last_name') ?? '';
$email = SessionManager::get('user_email') ?? '';
// dd(SessionManager::get('email'));
$phone = SessionManager::get('user_phone') ?? '';
?>
<div class="page-content">
    <?= FlashMessage::render() ?>
    <section class="aboutWrap">
        <div class="aboutText">
            <h2 class="aboutTitle">Solaf Performance</h2>
            <p>We combine luxury, reliability, and professionalism to deliver an exceptional travel experience. From airport transfers and regular pickups to corporate travel, special events, and long-distance journeys, our premium vehicles and expert chauffeurs ensure you arrive relaxed, refreshed, and on time. Every ride is crafted with comfort and elegance in mind — where sophistication meets convenience.</p>
        </div>
    </section>

    <section class="heroWrap">
        <div class="bookingWrap">
            <form class="bookingForm" method="post" action="<?= APP_USER_URL ?>/reservations/book">
                <ul class="bookingTabs">
                    <li><button type="button" id="trip" class="bookingTab active">Trip</button></li>
                    <li><button type="button" id="hourly" class="bookingTab">Hourly</button></li>
                    <input type="hidden" id="reservation_type" name="reservation_type" value="trip">
                </ul>

                <script>
                    document.addEventListener('DOMContentLoaded', initializeButtons);


                    function initializeButtons() {
                        const tripButton = document.getElementById('trip');
                        const hourlyButton = document.getElementById('hourly');
                        const reservation_type = document.getElementById('reservation_type');
                        const dropoffField = document.getElementById('dropoff');
                        const end_timeField = document.getElementById('end_time');

                        tripButton.addEventListener('click', function() {
                            tripButton.className = 'bookingTab active';
                            hourlyButton.className = 'bookingTab';

                            reservation_type.value = 'trip';
                            end_timeField.disabled = true;
                            dropoffField.disabled = false;
                        });

                        hourlyButton.addEventListener('click', function() {
                            hourlyButton.className = 'bookingTab active';
                            tripButton.className = 'bookingTab';

                            reservation_type.value = 'hourly';
                            end_timeField.disabled = false;
                            dropoffField.disabled = true;
                        });
                    }
                </script>

                <div class="row g-2 bookRow">
                    <div class="col-md">
                        <label for="first_name">First Name</label>
                        <input id="first_name" name="first_name" type="text" class="form-control" value="<?= $first_name ?>">
                    </div>
                    <div class="col-md">
                        <label for="last_name">Last Name</label>
                        <input id="last_name" name="last_name" type="text" class="form-control" value="<?= $last_name ?>">
                    </div>
                </div>

                <div class="row g-2 bookRow">
                    <div class="col-md">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" class="form-control" value="<?= $email ?>">
                    </div>
                    <div class="col-md">
                        <label for="phone">Phone</label>
                        <input id="phone" name="phone" type="text" class="form-control" value="<?= $phone ?>">
                    </div>
                </div>

                <div class="row g-2 bookRow">
                    <div class="col-md">
                        <label for="pickup">Pickup Location</label>
                        <input id="pickup" name="pickup" type="text" class="form-control">
                    </div>
                    <div class="col-md">
                        <label for="dropoff">Drop-off Location</label>
                        <input id="dropoff" name="dropoff" type="text" class="form-control">
                    </div>
                </div>

                <div class="row g-2 bookRow">
                    <div class="col-md">
                        <label for="start_time">Start Time</label>
                        <input id="start_time" name="start_time" type="datetime-local" class="form-control">
                    </div>

                    <div class="col-md">
                        <label for="end_time">End Time</label>
                        <input id="end_time" name="end_time" type="datetime-local" class="form-control" disabled="true">
                    </div>
                </div>
                <div class="row g-2 bookRow">
                    <div class="col-md">
                        <label for="cars_id">Vehicle</label>
                        <select name="cars_id" id="cars_id" class="form-select" required>
                            <!-- Placeholder for create view -->
                            <option value="" disabled selected>Select a vehicle</option>

                            <!-- Loop through all cars -->
                            <?php foreach ($cars as $car): ?>
                                <option
                                    value="<?= $car['cars_id'] ?>"
                                    <?= (isset($reservation['cars_id']) && $reservation['cars_id'] == $car['cars_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')') ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>
                <div class="row g-1 bookRow">
                    <div class="bookFull">
                        <label for="comments">Comments</label>
                        <textarea id="comments" name="comments" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="row g-1">
                    <div class="col-md text-end">
                        <button class="reserveBtn" action="submit">
                            Reserve
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <br>

    <section class="stepsWrap">
        <div class="stepsSection">
            <h2 class="stepsTitle">How it works</h2>
            <div class="stepsBox">
                <div class="stepItem">
                    <div class="stepIcon"><i class="bi bi-car-front-fill"></i></div>
                    <h4 class="stepHead">Reserve Your Car</h4>
                    <p class="stepBody">Browse our available vehicles and submit a reservation request for your preferred car and dates.</p>
                </div>
                <div class="stepItem">
                    <div class="stepIcon"><i class="bi bi-check-circle-fill"></i></div>
                    <h4 class="stepHead">Admin Confirmation</h4>
                    <p class="stepBody">Our team reviews your request, confirms availability, and provides the final rental price.</p>
                </div>
                <div class="stepItem">
                    <div class="stepIcon"><i class="bi bi-credit-card-2-front-fill"></i></div>
                    <h4 class="stepHead">Secure Booking</h4>
                    <p class="stepBody">Pay in person on the day of your reservation or online prior to your reservation.</p>
                </div>
                <div class="stepItem">
                    <div class="stepIcon"><i class="bi bi-clock-history"></i></div>
                    <h4 class="stepHead">Pickup & Go</h4>
                    <p class="stepBody">Your driver arrives at the scheduled time to take you to your destination safely and comfortably.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="carsWrap">
        <h2 class="stepsTitle">Our Cars</h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5" class="cars-section">
            <?php foreach ($threeCars as $car): ?>
                <div class="col">
                    <div class="car-card">
                        <!-- Carousel -->
                        <?php if (!empty($car['images'])): ?>
                            <div id="carousel<?= $car['cars_id'] ?>" class="carousel slide car-carousel" data-bs-ride="carousel" style="
                                height: 600px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                ">
                                <div class="carousel-indicators">
                                    <?php foreach ($car['images'] as $index => $image): ?>
                                        <button type="button"
                                            data-bs-target="#carousel<?= $car['cars_id'] ?>"
                                            data-bs-slide-to="<?= $index ?>"
                                            class="<?= $index === 0 ? 'active' : '' ?>"
                                            aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                                            aria-label="Slide <?= $index + 1 ?>">
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                                <div class="carousel-inner">
                                    <?php foreach ($car['images'] as $index => $image): ?>
                                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                            <img src="<?= APP_BASE_URL ?>/uploads/images/<?= htmlspecialchars($image['image_path']) ?>"
                                                class="d-block w-100"
                                                alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>"
                                                style="width:100%;">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $car['cars_id'] ?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $car['cars_id'] ?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 600px;">
                                <span class="text-white">No Image Available</span>
                            </div>
                        <?php endif; ?>

                        <!-- Card Body -->
                        <div class="car-card-body">
                            <h3 class="car-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' ' . $car['year']) ?></h3>
                            <p class="card-details">
                                <hr class="custom-line">

                            <div class="car-detail-item">
                                <span class="car-detail-label">Model:</span>
                                <span class="car-detail-value"><?= htmlspecialchars($car['model']) ?></span>
                            </div>
                            <div class="car-detail-item">
                                <span class="car-detail-label">Year:</span>
                                <span class="car-detail-value"><?= htmlspecialchars($car['year']) ?></span>
                            </div>
                            <div class="car-detail-item">
                                <span class="car-detail-label">Capacity:</span>
                                <span class="car-detail-value"><?= htmlspecialchars($car['capacity']) ?></span>
                            </div>
                            <div class="car-detail-item">
                                <span class="car-detail-label">Average Price:</span>
                                <span class="car-detail-value">$ <?= htmlspecialchars($car['approx_price']) ?> / hour</span>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a class="all-cars-link" href="<?= APP_USER_URL ?>/cars">View All Cars</a>
    </section>
</div>



<?php

// ViewHelper::loadJsScripts();
ViewHelper::loadCustomerFooter();
?>
