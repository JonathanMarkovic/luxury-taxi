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
                <!--ADD this to route and redirect to faqCreateView.php -->
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
                    <td> <?= $car['cars_id'] ?> </td>
                    <td><?= $car['brand'] ?></td>
                    <td><?= $car['model'] ?></td>
                    <td><?= $car['year'] ?></td>
                    <td><?= $car['capacity'] ?></td>
                    <td><?= $car['approx_price'] ?></td>
                    <td><?= $car['description'] ?></td>
                    <td><?= $car['created_at'] ?></td>
                    <td><?= $car['updated_at'] ?></td>
                </tr>
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