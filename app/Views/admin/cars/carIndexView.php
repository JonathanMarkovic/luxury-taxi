<?php

use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'List of Cars';
ViewHelper::loadAdminHeader($page_title);
$cars = $data['cars'];

?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="mb-4">
        <?= App\Helpers\FlashMessage::render() ?>
    </div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2><?= $page_title ?></h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div>
                <!--ADD this to route and redirect to carCreateView.php -->
                <a class="btn btn-primary" href="<?= APP_ADMIN_URL ?>/cars/create">+ New Car</a>
            </div>
        </div>
    </div>
    <!-- Canvas is the graph that would show on the main page -->
    <!-- <canvas
    <h2>Products List</h2>
        <-- Table of products should go here -->
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td>ID</td>
                <td>Brand</td>
                <td>Model</td>
                <td>Year</td>
                <td>Capacity</td>
                <td>Approximate Price</td>
                <td>Description</td>
                <td>Created at</td>
                <td>Updated at</td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $key => $car): ?>
                <!-- Create a <tr> element for each coffee shop in the list -->
                <tr>
                    <td> <?= htmlspecialchars($car['cars_id'], ENT_QUOTES, 'UTF-8') ?> </td>
                    <td><?= htmlspecialchars($car['brand'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($car['model'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($car['year'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($car['capacity'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($car['approx_price'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($car['description'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($car['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($car['updated_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <!-- ADD route and redirect to carEditView.php-->
                        <a class="btn btn-primary" href="<?= APP_ADMIN_URL ?>/cars/edit/<?= $car['cars_id'] ?>"> Edit</a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $car['cars_id'] ?>">Delete</button>
                    </td>
                </tr>

                <div class="modal fade" id="deleteModal<?= $car['cars_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel<?= $car['cars_id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="deleteModalLabel<?= $car['cars_id'] ?>">Attention!</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Would you really like to delete car: <?= $car['cars_id'] ?>?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="<?= APP_ADMIN_URL ?>/cars/delete/<?= $car['cars_id'] ?>" method="POST" style="display: inline;">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Client View</h2>
    <!-- Cards with Carousels -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        <?php foreach ($cars as $car): ?>
            <div class="col">
                <div class="card h-100">
                    <!-- Carousel -->
                    <?php if (!empty($car['images'])): ?>
                        <div id="carousel<?= $car['cars_id'] ?>" class="carousel slide" data-bs-ride="carousel">
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
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' ' . $car['year']) ?></h5>
                        <p class="card-text">
                            <strong>ID:</strong> <?= htmlspecialchars($car['cars_id']) ?><br>
                            <strong>Capacity:</strong> <?= htmlspecialchars($car['capacity']) ?> passengers<br>
                            <strong>Price:</strong> $<?= number_format($car['approx_price']) ?> / hour<br>
                            <small class="text-muted"><?= htmlspecialchars(substr($car['description'], 0, 100)) ?><?= strlen($car['description']) > 100 ? '...' : '' ?></small>
                        </p>
                    </div>

                    <!-- Card Footer with Actions -->
                    <div class="card-footer bg-transparent">
                        <div class="d-flex gap-2">
                            <a href="<?= APP_ADMIN_URL ?>/cars/edit/<?= $car['cars_id'] ?>" class="btn btn-sm btn-primary flex-fill">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $car['cars_id'] ?>">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal<?= $car['cars_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel<?= $car['cars_id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="deleteModalLabel<?= $car['cars_id'] ?>">Confirm Deletion</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <strong><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></strong>?
                            <br><small class="text-muted">This action cannot be undone.</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a href="<?= APP_ADMIN_URL ?>/cars/delete/<?= $car['cars_id'] ?>" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>


<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>
