<?php

use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'Home';
ViewHelper::loadHeader($page_title);
$cars = $data['cars'];
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    Share
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    Export
                </button>
            </div>
            <button
                type="button"
                class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1">
                <svg class="bi" aria-hidden="true">
                    <use xlink:href="#calendar3"></use>
                </svg>
                This week
            </button>
        </div>
    </div>
    <!-- Canvas is the graph that would show on the main page -->
    <!-- <canvas
    <h2>Products List</h2>
        <!-- Table of products should go here -->
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td>car id</td>
                <td>brand</td>
                <td>model</td>
                <td>year</td>
                <td>capacity</td>
                <td>approx_price</td>
                <td>description</td>
                <td>created at</td>
                <td>updated at</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $key => $car): ?>
                <!-- Create a <tr> element for each coffee shop in the list -->
                <tr>
                    <td> <?= $car['car_id'] ?> </td>
                    <td><?= $car['brand'] ?></td>
                    <td><?= $car['model'] ?></td>
                    <td><?= $car['year'] ?></td>
                    <td><?= $car['capacity'] ?></td>
                    <td><?= $car['approx_price'] ?></td>
                    <td><?= $car['description'] ?></td>
                    <td><?= $reservation['created_at'] ?></td>
                    <td><?= $reservation['updated_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>


<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>