<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\ReservationModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DashboardController extends BaseController
{
    public function __construct(Container $container,  private ReservationModel $reservation_model)
    {
        parent::__construct($container);
    }

    /**
     * Summary of index
     * The index page for the admin dashboard.
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return float|int
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $reservations=$this->reservation_model->fetchReservations();
        $events = array();

        foreach ($reservations as $reservation) {
            $color = null;
            if($reservation['reservation_status']=='pending'){
                $color= '#ffc107';
            };
            if($reservation['reservation_status']=='approved'){
                $color= '#198754';
            };
            if($reservation['reservation_status']=='cancelled'){
                $color= '#dc3545';
            };
            if($reservation['reservation_status']=='denied'){
                $color= '#6c757d';
            };
            if($reservation['reservation_status']=='completed'){
                $color= '#0d6efd';
            };

            $events[] =[
                'title' => $reservation['reservation_status'],
                'start' => $reservation['start_time'],
                'end' => $reservation['end_time'],
                'color' => $color
            ];

        };
        $data = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
            'events' => $events
        ];

        return $this->render(
            $response,
            '/admin/dashboardView.php',
            $data
        );
    }



}
