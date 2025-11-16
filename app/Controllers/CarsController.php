<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\CarModel;
use App\Helpers\FlashMessage;
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

    /**
     * Summary of create
     * Loads the car creation page for the user
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function create(Request $request, Response $response, array $args): Response
    {
        $data['data'] = [
            'title' => 'Cars',
            'message' => 'Welcome to the Cars Creation page'
        ];

        return $this->render($response, 'cars.create', $data);
    }

    /**
     * Summary of store
     * Extracts and validates the input data for a new car
     * before calling on the CarsModel to store the car's
     * information in the database.
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function store(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        //todo validate the values

        // Create and redirect
        $this->car_model->createAndGetId($data);
        return $this->redirect($request, $response, 'cars.index');
    }

    /**
     * Summary of delete
     * Extracts the car_id from the arguments section of the URI and sends it to
     * the CarsModel for removal from the database.
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $car_id = $args['car_id'];

        if (is_numeric($car_id)) {
            $this->car_model->deleteCar($car_id);
        }

        return $this->redirect($request, $response, 'cars.index');
    }

    /**
     * Summary of update
     * Extracts and validates the user inputs before
     * calling on the CarsModel class to update the car
     * @param Request $request
     * @param Response $reponse
     * @param array $args
     * @return Response
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $car_id = $args['car_id'];

        $data = $request->getParsedBody();

        //todo validate inputs here

        $this->car_model->updateCar($car_id, $data);
        FlashMessage::success("Car Added Successfully");

        return $this->redirect($request, $response, 'cars.index');
    }
}
