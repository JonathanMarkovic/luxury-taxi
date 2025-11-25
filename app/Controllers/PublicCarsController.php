<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\CarImageModel;
use App\Domain\Models\CarModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PublicCarsController extends BaseController
{
    public function __construct(Container $container, private CarModel $car_model, private CarImageModel $car_image_model)
    {
        parent::__construct(($container));
    }

    /**
    * Public car listing page - no authentication required
    */
    public function index(Request $request, Response $response, array $args): Response {
        $cars = $this->car_model->fetchCars();

        // & creates a copy of $car
        foreach ($cars as &$car) {
            $car['images'] = $this->car_image_model->fetchImagesById($car['cars_id']);
        }
        // unset($car);

        $data = [
            'title' => 'Our Cars',
            'cars' => $cars
        ];

        return $this->render($response, 'public/cars/carListView.php', $data);
    }
}
