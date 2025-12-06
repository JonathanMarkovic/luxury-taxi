<?php

use App\Helpers\FlashMessage;
use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'Home';
ViewHelper::loadCustomerHeader($page_title, 'home');
$cars = $data['cars'];
// dd($cars);
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
            <ul class="bookingTabs">
                <li><button class="bookingTab active">Transfer</button></li>
                <li><button class="bookingTab">Hourly</button></li>
            </ul>

            <form class="bookingForm">
                <div class="bookRow">
                    <div class="bookCol">
                        <label>First Name</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="bookCol">
                        <label>Last Name</label>
                        <input type="text" class="form-control">
                    </div>
                </div>

                <div class="bookRow">
                    <div class="bookCol">
                        <label>Email</label>
                        <div class="inputIcon"><i class="bi bi-envelope"></i></div>
                        <input type="email" class="form-control">
                    </div>


                    <div class="bookCol">
                        <label>Phone</label>
                        <div class="inputIcon"><i class="bi bi-telephone"></i></div>
                        <input type="text" class="form-control">
                    </div>
                </div>

                <div class="bookRow">
                    <div class="bookCol">
                        <label>Pickup</label>
                        <div class="inputIcon"><i class="bi bi-geo-alt"></i></div>
                        <input type="text" class="form-control">
                    </div>


                    <div class="bookCol">
                        <label>Dropoff</label>
                        <div class="inputIcon"><i class="bi bi-geo-alt"></i></div>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="bookRow">
                    <div class="bookCol">
                        <label>Date</label>
                        <div class="inputIcon"><i class="bi bi-calendar-event"></i></div>
                        <input type="date" class="form-control">
                    </div>

                    <div class="bookCol">
                        <label>Start Time</label>
                        <div class="inputIcon"><i class="bi bi-clock"></i></div>
                        <input type="time" class="form-control">
                    </div>


                    <div class="bookCol">
                        <label>End Time</label>
                        <div class="inputIcon"><i class="bi bi-clock"></i></div>
                        <input type="time" class="form-control">
                    </div>
                </div>


                <div class="bookRow">
                    <div class="bookFull">
                        <label>Comments</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <button class="reserveBtn">Reserve</button>
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
            <?php foreach ($cars as $car): ?>
                <div class="col">
                    <div class="car-card">
                        <!-- Carousel -->
                        <?php if (!empty($car['images'])): ?>
                            <div id="carousel<?= $car['cars_id'] ?>" class="carousel slide car-carousel" data-bs-ride="carousel">
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
                                                style="height: 250px; object-fit: cover;">
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
                            <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 250px;">
                                <span class="text-white">No Image Available</span>
                            </div>
                        <?php endif; ?>

                        <!-- Card Body -->
                        <div class="car-card-body">
                            <h3 class="car-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' ' . $car['year']) ?></h3>
                            <p class="card-details">
                            <div class="car-detail-item">
                                <span class="car-detail-label">Capacity:</span>
                                <span class="car-detail-value"><?= htmlspecialchars($car['capacity']) ?></span>
                            </div>
                            <div class="car-detail-item">
                                <span class="car-detail-label">Price:</span>
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
