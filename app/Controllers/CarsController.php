<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\CarModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CarsController extends BaseController
{
    public function __construct(Container $container, private CarModel $car_model)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $cars = $this->car_model->fetchCars();
        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
            'cars' => $cars
        ];

        return $this->render($response, 'admin/cars/carIndexView.php', $data);
    }
}
