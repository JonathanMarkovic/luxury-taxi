<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\ReservationModel;
use App\Domain\Models\UserModel;
use App\Helpers\FlashMessage;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReservationController extends BaseController
{
    public function __construct(Container $container, private ReservationModel $reservation_model, private UserModel $user_model)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $reservations = $this->reservation_model->fetchReservations();
        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
            'reservations' => $reservations
        ];

        return $this->render(
            $response,
            'admin/reservations/reservationIndexView.php',
            $data
        );
    }

    /**
     * Summary of create
     * Loads the reservation creation page for the user
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function create(Request $request, Response $response, array $args): Response
    {
        $data['data'] = [
            'title' => 'Reservations',
            'message' => 'Welcome to the Reservations Creation page'
        ];

        return $this->render($response, 'admin/reservations/reservationCreateView.php', $data);
    }

    /**
     * Summary of store
     * Extracts and validates the input data for a new reservation
     * before calling on the ReservationsModel to store the reservations's
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

        if (empty($data['email'])) {
            FlashMessage::error("Must include your email");
        } else {
            $user = $this->user_model->findByEmail($data['email']);
            $data['user_id'] = $user['user_id'];
        }

        $reservation_type = $data['reservation_type'];
        if ($reservation_type == null) {
            FlashMessage::error("Must choose a reservation type");
        } elseif ($reservation_type === 'trip') {
            if (empty($data['dropoff'])) {
                FlashMessage::error("Must include a dropoff location for trip reservations");
            }
        } elseif ($reservation_type === 'hourly') {
            if (empty($data['end_time'])) {
                FlashMessage::error("Must include an end time for hourly reservations");
            }
        }

        if (empty($data['pickup'])) {
            FlashMessage::error("Must include a pickup Address");
        }

        if (empty($data['start_time'])) {
            FlashMessage::error("Must include a start time");
        }


        // Create and redirect
        try {
            $this->reservation_model->createAndGetId($data);
        } catch (\Throwable $th) {
            FlashMessage::error("Something went wrong");
        }
        FlashMessage::success("Reservation added: You will get an email with your reservation details");

        $to = $data['email'];
        $subject = "Reservation Created";
        $message = "Solaf Performance has received your reservation request. You will get a response soon";
        $headers = "From: SOLAFEMAILHERE" . "\r\n" .
            "Reply-to: SOLAFEMAILHERE" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        return $this->redirect($request, $response, 'cars.index');
    }

    /**
     * Summary of delete
     * Extracts the cars_id from the arguments section of the URI and sends it to
     * the ReservationModel for removal from the database.
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $reservation_id = $args['reservation_id'];

        if (is_numeric($reservation_id)) {
            $this->reservation_model->deleteReservation($reservation_id);
        }

        return $this->redirect($request, $response, 'reservations.index');
    }

    /**
     * Summary of update
     * Extracts and validates the user inputs before
     * calling on the ReservationModel class to update the reservation
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $reservation_id = $args['reservation_id'];

        $data = $request->getParsedBody();

        //todo validate inputs here

        $this->reservation_model->updateReservation($reservation_id, $data);
        FlashMessage::success("Reservation Added Successfully");

        return $this->redirect($request, $response, 'cars.index');
    }
}
