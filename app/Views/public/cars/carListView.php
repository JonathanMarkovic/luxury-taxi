<?php

require_once 'customerHeader.php';

?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Find The Perfect Car</h1>
        <p>Explore our collection of premium vehicles designed to meet every travel need. Whether you're heading to the airport, a corporate event, or a night out, our cars combine performance, elegance, and luxury to deliver the perfect ride every time.</p>
    </div>
</section>

<!-- Cars Section -->
<section class="cars-section">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <!-- Car Card 1 -->
        <div class="col">
            <div class="car-card">
                <div id="carousel1" class="carousel slide car-carousel" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=800" alt="GMC Yukon Denali">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel1" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel1" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                <div class="car-card-body">
                    <h3 class="car-title">GMC Yukon Denali 2025</h3>
                    <div class="car-details">
                        <div class="car-detail-item">
                            <span class="car-detail-label">Brand:</span>
                            <span class="car-detail-value">GMC</span>
                        </div>
                        <div class="car-detail-item">
                            <span class="car-detail-label">Model:</span>
                            <span class="car-detail-value">Yukon Denali</span>
                        </div>
                        <div class="car-detail-item">
                            <span class="car-detail-label">Year:</span>
                            <span class="car-detail-value">2025</span>
                        </div>
                        <div class="car-detail-item">
                            <span class="car-detail-label">Capacity:</span>
                            <span class="car-detail-value">7</span>
                        </div>
                        <div class="car-detail-item" style="grid-column: 1 / -1;">
                            <span class="car-detail-label">Average Price:</span>
                            <span class="car-detail-value">$120/hour</span>
                        </div>
                    </div>
                    <p class="car-description">The 2025 GMC Yukon Denali blends commanding presence with refined luxury, featuring a 6.2-litre V8 delivering 420 hp and 460 lb-ft of torque alongside a 10-speed automatic transmission.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

require_once 'customerFooter.php';

?>
