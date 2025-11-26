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
        foreach ($reservations as $key => $reservation) {
            $customer = $this->user_model->fetchUserById($reservation['user_id']);
            // $reservation['email'] = $customer['email'];
            // dd($reservation);
        }
        // dd($reservations);
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

    public function show(Request $request, Response $response, array $args): Response {}

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

        $errors = [];
        // dd($data);

        if ($this->validate($data)) {
            if ($this->user_model->emailExists($data['email'])) {
                $user = $this->user_model->findByEmail($data['email']);
                // if email exists, grab user_id
                $data['user_id'] = $user['user_id'];
            }
            // dd($data);
            $this->reservation_model->createAndGetId($data);
        } else {
            return $this->redirect($request, $response, 'reservations.create');
        }
        FlashMessage::success("Reservation added: You will get an email with your reservation details");

        //TODO: FILL RESERVATION INFORMATION IN THE EMAIL
        $to = $data['email'];
        $subject = "Reservation Created";
        $message = "Solaf Performance has received your reservation request. You will get a response soon";
        $headers = "From: SOLAFEMAILHERE" . "\r\n" .
            "Reply-to: SOLAFEMAILHERE" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        // if (mail($to, $subject, $message, $headers)) {
        //     echo 'email sent';
        // } else {
        //     echo 'email not sent';
        // }

        return $this->redirect($request, $response, 'reservations.index');
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

        $data = $request->getParsedBody();

        $errors = [];

        if ($this->validate($data)) {
            if ($this->user_model->emailExists($data['email'])) {
                $user = $this->user_model->findByEmail($data['email']);
                // if email exists, grab user_id
                $data['user_id'] = $user['user_id'];
            }
            $this->reservation_model->updateReservation($reservation_id, $data);
        } else {
            return $this->redirect($request, $response, 'reservations.update');
        }


        // Create and redirect
        // try {

        // } catch (\Throwable $th) {
        //     $errors[] = "Something went wrong";
        // }



        FlashMessage::success("Reservation updated: You will get an email with your reservation details");

        //TODO: FILL RESERVATION INFORMATION IN THE EMAIL
        $to = $data['email'];
        $subject = "Reservation Created";
        $message = "Solaf Performance has received your reservation request. You will get a response soon";
        $headers = "From: SOLAFEMAILHERE" . "\r\n" .
            "Reply-to: SOLAFEMAILHERE" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        // if (mail($to, $subject, $message, $headers)) {
        //     echo 'email sent';
        // } else {
        //     echo 'email not sent';
        // }

        FlashMessage::success("Reservation Added Successfully");

        return $this->redirect($request, $response, 'cars.index');
    }

    /**
     * Summary of validate
     * Validates the user inputs for the Store and Update functions
     * @param array $data
     * @return void
     */
    private function validate(array $data): mixed
    {
        $errors = [];

        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $email = $data['email'];
        $phone = $data['phone'];
        $start_time = $data['start_time'];
        $end_time = $data['end_time'];
        $pickup = $data['pickup'];
        $dropoff = $data['dropoff'];

        // Check if email is empty
        if (empty($email)) {
            $errors[] = "Must include your email";

            // Check if the email exists
        }

        if ($this->user_model->emailExists($email)) {
            $user = $this->user_model->findByEmail($email);
            // if email exists, grab user_id
            $data['user_id'] = $user['user_id'];
        } else {
            // if user does not exist create the user
            if (
                empty($firstName) ||
                empty($lastName) ||
                empty($email) ||
                empty($phone) ||
                empty($start_time) ||
                empty($pickup)
            ) {
                $errors[] = "Please fill in all fields.";
            }

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Please enter a valid email format: example@email.com";
            }

            // Validate phone number format
            $pattern = "/^(?:\d{3}[- ]\d{3}[- ]\d{4}|\(\d{3}\)[ ]?\d{3}[- ]\d{4}|\d{10})$/";
            if (!filter_var($phone, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => $pattern)))) {
                $errors[] = "Please enter a valid phone number format: 123-456-7890, 123 456 7890, (123) 456 7890";
            }

            // Validation complete Create the user
            $data['user_id'] = $this->user_model->createGuestAndGetId($data);
        }

        // Checking reservation type related validation
        $reservation_type = $data['reservation_type'];
        if ($reservation_type == null) {
            $errors = "Must choose a reservation type";
        } elseif ($reservation_type === 'trip') {
            if (empty($dropoff)) {
                $errors[] = "Must include a dropoff location for trip reservations";
            }
        } elseif ($reservation_type === 'hourly') {
            if (empty($end_time)) {
                $errors[] = "Must include an end time for hourly reservations";
            }
        }

        // verifying pickup location
        if (empty($pickup)) {
            $errors[] = "Must include a pickup Address";
        }

        // Verifying Start time
        if (empty($start_time)) {
            $errors[] = "Must include a start time";
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                FlashMessage::error($error);
            }
            return false;
        } else {
            return true;
        }
    }

    private function confirmReservation(Request $request, Response $response, array $args): Response
    {
        $reservation_id = $args['reservation_id'];
        $this->reservation_model->approveReservation($reservation_id);
        FlashMessage::success("Reservation Confirmed");

        $start_time = $args['start_time'];

        // TODO: NEED TO INCLUDE THE EMAIL FOR SOLAF PERFORMANCE AND ACTUALLY SEND THE EMAIL HERE
        $to = $args['email'];
        $subject = "Reservation Confirmed";
        $message = "Hello your reservation at $start_time has been approved by Solaf Performance";
        $headers = "From: SOLAFEMAILHERE" . "\r\n" .
            "Reply-to: SOLAFEMAILHERE" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        // if (mail($to, $subject, $message, $headers)) {
        //     echo 'email sent';
        // } else {
        //     echo 'email not sent';
        // }

        return $this->index($request, $response, $args);
    }

    private function denyReservation(Request $request, Response $response, array $args): Response
    {
        $reservation_id = $args['reservation_id'];
        $this->reservation_model->denyReservation($reservation_id);
        FlashMessage::success("Reservation Denied");

        $start_time = $args['start_time'];

        // TODO: NEED TO INCLUDE THE EMAIL FOR SOLAF PERFORMANCE AND ACTUALLY SEND THE EMAIL HERE
        $to = $args['email'];
        $subject = "Reservation Confirmed";
        $message = "Hello your reservation at $start_time has been denied by Solaf Performance";
        $headers = "From: SOLAFEMAILHERE" . "\r\n" .
            "Reply-to: SOLAFEMAILHERE" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        // if (mail($to, $subject, $message, $headers)) {
        //     echo 'email sent';
        // } else {
        //     echo 'email not sent';
        // }

        return $this->index($request, $response, $args);
    }

    private function cancelReservation(Request $request, Response $response, array $args): Response
    {
        $reservation_id = $args['reservation_id'];
        $this->reservation_model->cancelReservation($reservation_id);
        FlashMessage::success("Reservation Cancelled");

        $start_time = $args['start_time'];

        // TODO: NEED TO INCLUDE THE EMAIL FOR SOLAF PERFORMANCE AND ACTUALLY SEND THE EMAIL HERE
        $to = $args['email'];
        $subject = "Reservation Confirmed";
        $message = "Hello your reservation at $start_time has been Cancelled";
        $headers = "From: SOLAFEMAILHERE" . "\r\n" .
            "Reply-to: SOLAFEMAILHERE" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        // if (mail($to, $subject, $message, $headers)) {
        //     echo 'email sent';
        // } else {
        //     echo 'email not sent';
        // }

        return $this->index($request, $response, $args);
    }
}
