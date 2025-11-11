<?php

declare(strict_types=1);

/**
 * This file contains the routes for the web application.
 */

use App\Controllers\CarsController;
use App\Controllers\CustomerController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
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

        $group->get('/cars', [CarsController::class, 'index']);

        $group->get('/reservations', [ReservationController::class, 'index']);

        $group->get('/customers', [UserController::class, 'index']);
    });

    // A route to test runtime error handling and custom exceptions.
    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpNotFoundException($request, "Something went wrong");
    });
};
