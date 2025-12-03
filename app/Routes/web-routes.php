<?php

declare(strict_types=1);

/**
 * This file contains the routes for the web application.
 */

use App\Controllers\AuthController;
use App\Controllers\CarImageController;
use App\Controllers\CarsController;
use App\Controllers\CustomerController;
use App\Controllers\DashboardController;
use App\Controllers\FAQController;
use App\Controllers\HomeController;
use App\Controllers\PaymentController;
use App\Controllers\PublicCarsController;
use App\Controllers\PublicFaqController;
use App\Controllers\UserController;
use App\Controllers\ReservationController;
use App\Middleware\AdminAuthMiddleware;
use App\Middleware\AuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return static function (Slim\App $app): void {
    //* NOTE: Route naming pattern: [controller_name].[method_name]
    $app->get('/', [HomeController::class, 'index'])
        ->setName('home.index');

    $app->get('/home', [HomeController::class, 'index'])
        ->setName('home.index');

    // Public Routes
    $app->group('/public', function ($group) {
        // Public car list
        $group->get('/cars', [PublicCarsController::class, 'index'])->setName('public.cars');
        // $group->get('/reservations', [PublicCarsController::class, 'index'])->setName('public.reservations');
        $group->get('/faqs', [PublicFaqController::class, 'index'])->setName('public.faqs');
        $group->post('/faqs/question', [PublicFaqController::class, 'submit']);
    });

    // Admin Routes
    $app->group('/admin', function ($group) {
        $group->get('/dashboard', [DashboardController::class, 'index'])->setName('admin.dashboard');

        //* Cars Routes
        $group->get('/cars', [CarsController::class, 'index'])->setName('cars.index');
        $group->get('/cars/create', [CarsController::class, 'create'])->setName('cars.create');
        $group->post('/cars/store', [CarsController::class, 'store'])->setName('cars.store');
        $group->post('/cars/delete/{cars_id}', [CarsController::class, 'delete'])->setName('cars.delete');
        $group->get('/cars/edit/{cars_id}', [CarsController::class, 'edit'])->setName('cars.edit');
        $group->post('/cars/update/{cars_id}', [CarsController::class, 'update'])->setName('cars.update');


        //* Car Images Routes
        $group->get('/carImage', [CarImageController::class, 'index'])->setName('carImage.index');
        $group->get('/carImage/upload/{car_image_id}', [CarImageController::class, 'upload'])->setName('carImage.upload');
        $group->post('/carImage', [CarImageController::class, 'store']);
        $group->get('/carImage/delete/{image_id}', [CarImageController::class, 'delete'])->setName('carImage.delete');
        $group->post('/carImage/update/{image_id}', [CarImageController::class, 'update']);

        //* Reservations Routes
        $group->get('/reservations', [ReservationController::class, 'index'])->setName('reservations.index');
        $group->get('/reservations/create', [ReservationController::class, 'create'])->setName('reservations.create');
        $group->post('/reservations/store', [ReservationController::class, 'store']);
        $group->get('/reservations/delete/{reservation_id}', [ReservationController::class, 'delete'])->setName('reservations.delete');
        $group->post('/reservations/update/{reservation_id}', [ReservationController::class, 'update']);
        $group->get('/reservations/view/{reservation_id}', [ReservationController::class, 'view']);
        $group->post('/reservations/submit/{reservation_id}', [ReservationController::class, 'submitReservation']);

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
    })->add(AdminAuthMiddleware::class);

    //* Login & Registration Routes (public)
    $app->get('/register', [AuthController::class, 'register'])->setName("auth.register");
    $app->post('/register', [AuthController::class, 'store']);
    $app->get('/login', [AuthController::class, 'login'])->setName('auth.login');
    $app->post('/login', [AuthController::class, 'authenticate']);
    $app->get('/logout', [AuthController::class, 'logout'])->setName('auth.logout');

    //* User Routes
    $app->get('/dashboard', [AuthController::class, 'dashboard'])->setName('user.dashboard')->add(AuthMiddleware::class);

    //* Payment Routes
    $app->get('/payment', [PaymentController::class, 'index'])->setName('user.payment');
    $app->post('/payment', [PaymentController::class, 'pay']);
};
