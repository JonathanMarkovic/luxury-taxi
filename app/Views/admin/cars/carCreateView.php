<?php

use App\Helpers\ViewHelper;

$page_title = 'Create Car';
ViewHelper::loadAdminHeader($page_title);

// require_once('admin_header');
?>

<form action="" method="POST">
    <div class="d-grid gap-3" style="margin: 50px;">
        <div class="row g-2"> <!-- First row container -->
            <div class="col-md">
                <!-- Brand input -->
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInputGrid" name="brand">
                    <label for="floatingInputGrid">Brand</label>
                </div>
            </div>
            <div class="col-md">
                <!-- Model input -->
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInputGrid" name="model">
                    <label for="floatingInputGrid">Model</label>
                </div>
            </div>
            <div class="col-md">
                <!-- Year input -->
                <div class="form-floating">
                    <input type="number" class="form-control" id="floatingInputGrid" name="year">
                    <label for="floatingInputGrid">Year</label>
                </div>
            </div>
        </div>
        <div class="row g-2"> <!-- Second row container -->
            <div class="col-md">
                <!-- Capacity input -->
                <div class="form-floating">
                    <input type="number" class="form-control" id="floatingInputGrid" name="capacity">
                    <label for="floatingInputGrid">Capacity</label>
                </div>
            </div>
            <div class="col-md">
                <!-- Price input -->
                <div class="form-floating">
                    <input type="number" class="form-control" id="floatingInputGrid" name="approx_price">
                    <label for="floatingInputGrid">Price</label>
                </div>
            </div>
        </div>
        <div class="form-floating">
            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" name="description"></textarea>
            <label for="floatingTextarea2">Description</label>
        </div>
        <!-- <div class="mb-3">
            <input
                type="file"
                class="form-control"
                id="myfile"
                name="myfile"
                accept="image/*"
                multiple
                required>
            <div class="form-text">
                Select one or more images to upload (JPEG, PNG).
            </div>
        </div> -->
        <div class="button-container" style="text-align: right;">
            <!-- Cancel button (redirect to cars index view) -->
            <button type="button" class="btn btn-danger" onclick="">Cancel</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </div>
</form>
