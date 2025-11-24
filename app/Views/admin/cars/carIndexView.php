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
    <div id="car-list-container">

    </div>

</main>


<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>
