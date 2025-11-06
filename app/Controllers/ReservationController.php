<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\ReservationModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReservationController extends BaseController
{
    public function __construct(Container $container, private ReservationModel $reservation_model)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $reservations = $this->reservation_model->fetchReservations();
        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
            'reservation' => $reservations
        ];

        return $this->render(
            $response,
            'admin/reservations/reservationIndexView.php',
            $data
        );
    }
}
