<?php

use App\Helpers\ViewHelper;

$page_title = 'List of Cars';
ViewHelper::loadCustomerHeader($page_title, 'cars');
$cars = $data['cars'];
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Find The Perfect Car</h1>
        <p>Explore our collection of premium vehicles designed to meet every travel need. Whether you're heading to the airport, a corporate event, or a night out, our cars combine performance, elegance, and luxury to deliver the perfect ride every time.</p>
    </div>
</section>

<div class="page-content" style="padding-top:80px">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5" class="cars-section">
        <?php foreach ($cars as $car): ?>
            <div class="col">
                <div class="car-card">
                    <!-- Carousel -->
                    <?php if (!empty($car['images'])): ?>
                        <div id="carousel<?= $car['cars_id'] ?>" class="carousel slide car-carousel" data-bs-ride="carousel" >
                            <div class="carousel-indicators" style="object-fit:cover">
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
                                            >
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
                        <div class="bg-secondary d-flex align-items-center justify-content-center" >
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
                            <span class="car-detail-value"><?= htmlspecialchars($car['year'])?></span>
                        </div>
                        <div class="car-detail-item">
                            <span class="car-detail-label">Capacity:</span>
                            <span class="car-detail-value"><?= htmlspecialchars($car['capacity']) ?></span>
                        </div>
                        <div class="car-detail-item">
                            <span class="car-detail-label">Average Price:</span>
                            <span class="car-detail-value">$ <?= htmlspecialchars($car['approx_price'])?> / hour</span>
                        </div>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
ViewHelper::loadCustomerFooter();
?>
