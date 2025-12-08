<?php

use App\Helpers\ViewHelper;

$page_title = 'View Reservation';

$reservation = $data['reservations'];
$car = $data['car'];
$cars = $data['cars'];


ViewHelper::loadAdminHeader($page_title);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <!-- Flash Messages Display Area -->
    <div class="mb-4">
        <?= App\Helpers\FlashMessage::render() ?>
    </div>
    <h2>Reservation Viewing</h2>
    <br>
    <div class="row">
        <!-- Left side: Form -->
        <div class="col-md-6">
            <form id="updateForm<?= $reservation['reservation_id'] ?>" method="POST"
                action="<?= APP_ADMIN_URL ?>/reservations/submit/<?= $reservation['reservation_id'] ?>">
                <input type="hidden" name="reservation_id" value="<?= $reservation['reservation_id'] ?>">

                <div class="row g-3 mb-3"> <!-- First row -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $reservation['first_name'] ?>">
                            <label for="first_name">First Name</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $reservation['last_name'] ?>">
                            <label for="last_name">Last Name</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3"> <!-- Second row -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" value="<?= $reservation['email'] ?>">
                            <label for="email">User Email</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= $reservation['phone'] ?>">
                            <label for="phone">Phone Number</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3"> <!-- Third row -->
                    <div class="col-md-12">
                        <label for="reservation_type" class="form-label">Reservation Type</label>
                        <div class="form-floating">
                            <select name="reservation_type" id="reservation_type" class="form-select">
                                <option value="hourly" <?= $reservation['reservation_type'] == 'hourly' ? 'selected' : '' ?>>Hourly</option>
                                <option value="trip" <?= $reservation['reservation_type'] == 'trip' ? 'selected' : '' ?>>Trip</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-12 position-relative">
                            <select name="cars_id" id="cars_id" class="form-select custom-floating-select">

                                <!-- Placeholder for create view -->
                                <?php if (!isset($reservation['cars_id'])): ?>
                                    <option value="" disabled selected>Select a vehicle</option>
                                <?php endif; ?>

                                <!-- Loop through all cars -->
                                <?php foreach ($cars as $car): ?>
                                    <option
                                        value="<?= $car['cars_id'] ?>"
                                        <?= (isset($reservation['cars_id']) && $reservation['cars_id'] == $car['cars_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>

                            <label for="cars_id" class="floating-label">Vehicle</label>
                        </div>
                    </div>
                </div>


                <div class="row g-3 mb-3"> <!-- Fourth row -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="<?= $reservation['start_time'] ?>">
                            <label for="start_time">Start Time</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="<?= $reservation['end_time'] ?>">
                            <label for="end_time">End Time</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3"> <!-- Fifth row -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="pickup" name="pickup" value="<?= $reservation['pickup'] ?>">
                            <label for="pickup">Pickup Location</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="dropoff" name="dropoff" value="<?= $reservation['dropoff'] ?>">
                            <label for="dropoff">Dropoff Location</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3"> <!-- Sixth row -->
                    <div class="col-md-12">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Leave a comment here" id="comments" style="height: 100px" name="comments"><?= $reservation['comments'] ?></textarea>
                            <label for="comments">Comments</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3"> <!-- Seventh row -->
                    <div class="col-md-12">
                        <label for="reservation_status" class="form-label">Reservation Status</label>
                        <select name="reservation_status" id="reservation_status" class="form-select">
                            <option value="pending" <?= $reservation['reservation_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= $reservation['reservation_status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="denied" <?= $reservation['reservation_status'] == 'denied' ? 'selected' : '' ?>>Denied</option>
                            <option value="refunded" <?= $reservation['reservation_status'] == 'refunded' ? 'selected' : '' ?>>Refunded</option>
                            <option value="completed" <?= $reservation['reservation_status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $reservation['reservation_status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
                <!-- Second Row: buttons -->
                <div class="row g-3 mb-3 align-items-end">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= $reservation['price'] ?? '' ?>">
                            <label for="price">Price ($)</label>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <a href="<?= APP_ADMIN_URL ?>/reservations" class="btn btn-secondary me-2">Cancel</a>
                        <button type="button" class="btn btn-primary me-2" id="refundBtn<?= $reservation['reservation_id'] ?>">Refund</button>
                        <button type="button" class="btn btn-danger me-2" id="denyBtn<?= $reservation['reservation_id'] ?>">Deny</button>
                        <button type="button" class="btn btn-success" id="approveBtn<?= $reservation['reservation_id'] ?>">Approve</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Google maps, add later -->
        <div class="col-md-6">
            <div class="border rounded p-3" style="height: 87%; min-height: 600px; background-color: #f8f9fa;" id="map">
                <p class="text-center text-muted mt-5">Google Maps</p>
            </div>
            <br>
            <div>
                <button onclick="calculateRoute()" class="btn btn-secondary">View Direction</button>
            </div>
        </div>
    </div>




    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal<?= $reservation['reservation_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="approveModalLabel<?= $reservation['reservation_id'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h1 class="modal-title fs-5" id="approveModalLabel<?= $reservation['reservation_id'] ?>">Approve Reservation</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to approve this reservation?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
                    <button type="submit" form="updateForm<?= $reservation['reservation_id'] ?>" name="approve" class="btn btn-success">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Deny Modal -->
    <div class="modal fade" id="denyModal<?= $reservation['reservation_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="denyModalLabel<?= $reservation['reservation_id'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h1 class="modal-title fs-5" id="denyModalLabel<?= $reservation['reservation_id'] ?>">Deny Reservation</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to deny this reservation?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
                    <button type="submit" form="updateForm<?= $reservation['reservation_id'] ?>" name="deny" class="btn btn-danger">Deny</button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Refund Modal -->
<div class="modal fade" id="refundModal<?= $reservation['reservation_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="refundModalLabel<?= $reservation['reservation_id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h1 class="modal-title fs-5" id="refundModalLabel<?= $reservation['reservation_id'] ?>">Refund Reservation</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to refund this reservation?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
                <button type="submit" form="updateForm<?= $reservation['reservation_id'] ?>" name="refund" class="btn btn-primary">Refund</button>
            </div>
        </div>
    </div>
</div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("updateForm<?= $reservation['reservation_id'] ?>");
        const approveButton = document.getElementById("approveBtn<?= $reservation['reservation_id'] ?>");
        const denyButton = document.getElementById("denyBtn<?= $reservation['reservation_id'] ?>");
        const refundButton = document.getElementById("refundBtn<?= $reservation['reservation_id'] ?>");
        const priceInput = document.getElementById("price");
        const approveModal = new bootstrap.Modal(document.getElementById("approveModal<?= $reservation['reservation_id'] ?>"));
        const denyModal = new bootstrap.Modal(document.getElementById("denyModal<?= $reservation['reservation_id'] ?>"));
        const refundModal = new bootstrap.Modal(document.getElementById("refundModal<?= $reservation['reservation_id'] ?>"));

        approveButton.addEventListener("click", function(e) {
            // Add required for approve
            priceInput.setAttribute('required', 'required');

            if (!priceInput.checkValidity()) {
                priceInput.reportValidity();
            } else {
                approveModal.show();
            }
        });

        denyButton.addEventListener("click", function(e) {
            // Remove required for deny
            priceInput.removeAttribute('required');
            denyModal.show();
        });

        refundButton.addEventListener("click", function(e) {
            // Remove required for refund
            priceInput.removeAttribute('required');
            refundModal.show();
        });
    });
</script>

<script>
    let map, directionService, directionRenderer;
    let mapsLoaded = false;
    let mapsLoading = false;

    function loadGoogleMaps() {
        return new Promise((resolve, reject) => {
            //check if the map is loaded
            if (typeof google !== 'undefined' && google.maps) {
                mapsLoaded = true;
                resolve();
                return;
            }

            //if its currently loading
            if (mapsLoading) {
                const checkLoaded = setInterval(() => {
                    if (typeof google !== 'undefined' && google.maps) {
                        clearInterval(checkLoaded);
                        resolve();
                    }
                }, 100);
                return;
            }

            mapsLoading = true;

            window.initMap = function() {
                console.log("Google Maps API loaded successfully");
                mapsLoaded = true;
                mapsLoading = false;
                resolve();
            };

            const script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDNMWPTWGhqZ0rJzKSSsk9EP-YUupehQfw&libraries=places&callback=initMap';
            script.defer = true;
            script.onerror = (error) => {
                console.error("Failed to load Google Maps script:", error);
                mapsLoading = false;
                reject(new Error("Failed to load Google Maps"));
            };

            document.head.appendChild(script);
            console.log("Script tag added to document");
        });
    }

    function initializeMap() {
        console.log("Initializing map...");
        const mapElement = document.getElementById("map");

        if (!mapElement) {
            console.error("Map element not found");
            return;
        }

        map = new google.maps.Map(mapElement, {
            center: {
                lat: 45.508888,
                lng: -73.561668
            },
            zoom: 12,
        });

        directionService = new google.maps.DirectionsService();
        directionRenderer = new google.maps.DirectionsRenderer();
        directionRenderer.setMap(map);
        console.log("Map initialized successfully");
    }

    async function calculateRoute() {
        console.log("calculateRoute called");
        var source = document.getElementById("pickup").value;
        var destination = document.getElementById("dropoff").value;

        console.log("Source:", source, "Destination:", destination);

        if (!source || !destination) {
            alert("Please enter both pickup and dropoff locations");
            return;
        }

        try {
            console.log("Loading Google Maps...");
            await loadGoogleMaps();
            console.log("Google Maps loaded, checking map initialization...");

            if (!map) {
                initializeMap();
            }

            var request = {
                origin: source,
                destination: destination,
                travelMode: 'DRIVING'
            };

            console.log("Requesting directions with:", request);
            directionService.route(request, function(result, status) {
                console.log("Directions response - Status:", status);
                if (status == 'OK') {
                    directionRenderer.setDirections(result);
                    console.log("Directions rendered successfully");
                } else {
                    console.error("Directions failed:", status, result);
                    alert("Could not display directions due to: " + status);
                }
            });
        } catch (error) {
            console.error("Error in calculateRoute:", error);
            alert("Failed to load Google Maps. Error: " + error.message);
        }
    }
</script>
<?php
ViewHelper::loadJsScripts();
//TODO: we need to load an admin-specific footer
ViewHelper::loadAdminFooter();
?>
