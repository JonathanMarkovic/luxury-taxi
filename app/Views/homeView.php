<?php

use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use App\Helpers\ViewHelper;

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


    <section class="heroWrap">
        <div class="bookingWrap">
            <form class="bookingForm" method="post" action="<?= APP_USER_URL ?>/reservations/book">
                <ul class="bookingTabs">
                    <li><button type="button" id="trip" class="bookingTab active"><?= hs(trans('home.trip')) ?></button></li>
                    <li><button type="button" id="hourly" class="bookingTab"><?= hs(trans('home.hourly')) ?></button></li>
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
                        <label for="first_name"><?= hs(trans('home.firstN')) ?></label>
                        <input id="first_name" name="first_name" type="text" class="form-control" value="<?= $first_name ?>">
                    </div>
                    <div class="col-md">
                        <label for="last_name"><?= hs(trans('home.lastN')) ?></label>
                        <input id="last_name" name="last_name" type="text" class="form-control" value="<?= $last_name ?>">
                    </div>
                </div>

                <div class="row g-2 bookRow">
                    <div class="col-md">
                        <label for="email"><?= hs(trans('home.email')) ?></label>
                        <input id="email" name="email" type="email" class="form-control" value="<?= $email ?>">
                    </div>
                    <div class="col-md">
                        <label for="phone"><?= hs(trans('home.phone')) ?></label>
                        <input id="phone" name="phone" type="text" class="form-control" value="<?= $phone ?>">
                    </div>
                </div>

                <div class="row g-2 bookRow">
                    <div class="col-md">
                        <label for="pickup"><?= hs(trans('home.pickup')) ?></label>
                        <input id="pickup" name="pickup" type="text" class="form-control">
                    </div>
                    <div class="col-md">
                        <label for="dropoff"><?= hs(trans('home.dropoff')) ?></label>
                        <input id="dropoff" name="dropoff" type="text" class="form-control">
                    </div>
                </div>

                <div class="row g-2 bookRow">
                    <div class="col-md">
                        <label for="start_time"><?= hs(trans('home.start')) ?></label>
                        <input id="start_time" name="start_time" type="datetime-local" class="form-control">
                    </div>

                    <div class="col-md">
                        <label for="end_time"><?= hs(trans('home.end')) ?></label>
                        <input id="end_time" name="end_time" type="datetime-local" class="form-control" >
                    </div>
                </div>
                <div class="row g-2 bookRow">
                    <div class="col-md">
                        <label for="cars_id"><?= hs(trans('home.vehicle')) ?></label>
                        <select name="cars_id" id="cars_id" class="form-select" required>
                            <!-- Placeholder for create view -->
                            <option value="" disabled selected><?= hs(trans('home.selectVehicle')) ?></option>

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
                        <label for="comments"><?= hs(trans('home.comments')) ?></label>
                        <textarea id="comments" name="comments" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="row g-1">
                    <div class="col-md text-center">
                        <button class="reserveBtn" action="submit">
                            <?= hs(trans('home.reserve')) ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <br>

<section class="about">
    <div class="about-grid">

        <!-- TOP LEFT IMAGE -->
        <div class="about-img about-img-1"></div>

        <!-- TITLE BOX (OVERLAPPING) -->
        <div class="about-title-box">
            <h1>Canada<br>Montreal</h1>
        </div>

        <!-- BOTTOM LEFT TEXT -->
        <div class="about-text">
            <br><br>
            <h2>Solaf Performance</h2>
            <br>
            <p>
               <?= hs(trans('home.description')) ?>
            </p>
        </div>

        <!-- BOTTOM RIGHT IMAGE -->
        <div class="about-img about-img-2"></div>

    </div>
</section>

    <br><br><br>

    <section class="stepsWrap">
        <div class="stepsSection">
            <h2 class="stepsTitle"><?= hs(trans('home.howItWorks')) ?></h2>
            <br><br>
            <div class="stepsBox">
                <div class="stepItem">
                    <div class="stepIcon"><i class="bi bi-car-front-fill"></i></div>
                    <h4 class="stepHead"><?= hs(trans('home.works1')) ?></h4>
                    <p class="stepBody"><?= hs(trans('home.works1Desc')) ?></p>
                </div>
                <div class="stepItem">
                    <div class="stepIcon"><i class="bi bi-check-circle-fill"></i></div>
                    <h4 class="stepHead"><?= hs(trans('home.works2')) ?></h4>
                    <p class="stepBody"><?= hs(trans('home.works2Desc')) ?></p>
                </div>
                <div class="stepItem">
                    <div class="stepIcon"><i class="bi bi-credit-card-2-front-fill"></i></div>
                    <h4 class="stepHead"><?= hs(trans('home.works3')) ?></h4>
                    <p class="stepBody"><?= hs(trans('home.works3Desc')) ?></p>
                </div>
                <div class="stepItem">
                    <div class="stepIcon"><i class="bi bi-clock-history"></i></div>
                    <h4 class="stepHead"><?= hs(trans('home.works4')) ?></h4>
                    <p class="stepBody"><?= hs(trans('home.works4Desc')) ?></p>
                </div>
            </div>
        </div>
    </section>
    <section class="carsWrap">
        <h2 class="stepsTitle"><?= hs(trans('home.ourCars')) ?></h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5" class="cars-section">
            <?php foreach ($threeCars as $car): ?>
                <div class="col">
                    <div class="car-card">
                        <!-- Carousel -->
                        <?php if (!empty($car['images'])): ?>
                            <div id="carousel<?= $car['cars_id'] ?>" class="carousel slide car-carousel" data-bs-ride="carousel" >
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
                                            <img src="<?= APP_USER_URL ?>/uploads/images/<?= htmlspecialchars($image['image_path']) ?>"
                                                alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>" class="d-block w-100">
                                            
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
                            <div class="bg-secondary d-flex align-items-center justify-content-center">
                                <span class="text-white">No Image Available</span>
                            </div>
                        <?php endif; ?>

                        <!-- Card Body -->
                        <div class="car-card-body">
                            <h3 class="car-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' ' . $car['year']) ?></h3>
                            <p class="card-details">
                                <hr class="custom-line">

                            <div class="car-detail-item">
                                <span class="car-detail-label"><?= hs(trans('home.model')) ?>:</span>
                                <span class="car-detail-value"><?= htmlspecialchars($car['model']) ?></span>
                            </div>
                            <div class="car-detail-item">
                                <span class="car-detail-label"><?= hs(trans('home.year')) ?>:</span>
                                <span class="car-detail-value"><?= htmlspecialchars($car['year']) ?></span>
                            </div>
                            <div class="car-detail-item">
                                <span class="car-detail-label"><?= hs(trans('home.capacity')) ?>:</span>
                                <span class="car-detail-value"><?= htmlspecialchars($car['capacity']) ?></span>
                            </div>
                            <div class="car-detail-item">
                                <span class="car-detail-label"><?= hs(trans('home.avgPrice')) ?>:</span>
                                <span class="car-detail-value">$ <?= htmlspecialchars($car['approx_price']) ?> / hour</span>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a class="all-cars-link" href="<?= APP_USER_URL ?>/cars"><?= hs(trans('home.carsButton')) ?></a>
    </section>
</div>



<?php

// ViewHelper::loadJsScripts();
ViewHelper::loadCustomerFooter();
?>
