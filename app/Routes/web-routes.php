<?php

declare(strict_types=1);

/**
 * This file contains the routes for the web application.
 */

use App\Controllers\CarImageController;
use App\Controllers\CarsController;
use App\Controllers\CustomerController;
use App\Controllers\DashboardController;
use App\Controllers\FAQController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\ReservationController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return static function (Slim\App $app): void {
    //* NOTE: Route naming pattern: [controller_name].[method_name]
    $app->get('/', [HomeController::class, 'index'])
        ->setName('home.index');

    $app->get('/home', [HomeController::class, 'index'])
        ->setName('home.index');

    $app->group('/admin', function ($group) {
        $group->get('/dashboard', [DashboardController::class, 'index']);

        //* Cars Routes
        $group->get('/cars', [CarsController::class, 'index'])->setName('cars.index');
        $group->get('/cars/create', [CarsController::class, 'create'])->setName('cars.create');
        $group->post('/cars', [CarsController::class, 'store']);
        $group->get('/cars/delete/{car_id}', [CarsController::class, 'delete'])->setName('cars.delete');
        $group->post('/cars/update/{car_is}', [CarsController::class, 'update']);

        //* Car Images Routes
        $group->get('/carImage', [CarImageController::class, 'index'])->setName('carImage.index');
        $group->get('/carImage/upload/{car_image_id}', [CarImageController::class, 'upload'])->setName('carImage.upload');
        $group->post('/carImage', [CarImageController::class, 'store']);
        $group->get('/carImage/delete/{car_image_id}', [CarImageController::class, 'delete'])->setName('carImage.delete');
        $group->post('/carImage/update/{car_image_id}', [CarImageController::class, 'update']);

        //* Reservations Routes
        $group->get('/reservations', [ReservationController::class, 'index'])->setName('reservations.index');
        $group->get('reservations/create', [ReservationController::class, 'create'])->setName('reservations.create');
        $group->post('/reservations', [CarsController::class, 'store']);
        $group->get('/reservations/delete/{reservation_id}', [CarsController::class, 'delete'])->setName('reservations.delete');
        $group->post('/reservations/update/{reservation_id}', [CarsController::class, 'update']);

        //* Customers Routes
        $group->get('/customers', [UserController::class, 'index']);

        //* FAQ routes
        $group->get('/faq', [FAQController::class, 'index']);
        $group->get('/faq/edit/{faq_id}', [FAQController::class, 'edit']);
        $group->post('/faq/update/{faq_id}', [FAQController::class, 'update']);
        $group->get('/faq/add', [FAQController::class, 'add']);
        $group->post('/faq/create', [FAQController::class, 'create']);
        $group->get('/faq/delete/{faq_id}', [FAQController::class, 'delete']);
    });

    // A route to test runtime error handling and custom exceptions.
    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpNotFoundException($request, "Something went wrong");
    });
};
