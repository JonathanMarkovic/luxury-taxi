<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\PaymentModel;
use App\Domain\Models\ReservationModel;
use App\Domain\Models\UserModel;
use App\Domain\Models\CarModel;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\DateTime;
use DateTime as GlobalDateTime;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


class ReservationController extends BaseController
{
    public function __construct(Container $container, private ReservationModel $reservation_model, private UserModel $user_model, private PaymentModel $payment_model, private CarModel $car_model)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $reservations = $this->reservation_model->fetchReservations();
        $cars = $this->car_model->fetchCars();
        foreach ($reservations as $key => $reservation) {
            $customer = $this->user_model->fetchUserById($reservation['user_id']);
            $payment = $this->payment_model->fetchPaymentByID($reservation['reservation_id']);
            $reservations[$key]['price'] = $payment['total_amount'] ?? null;
            $reservations[$key]['total_paid'] = $payment['total_paid'] ?? null;
        }
        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
            'reservations' => $reservations,
            'cars' => $cars
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

    //mkaes a new reservtion from admin side
    public function create(Request $request, Response $response, array $args): Response
    {
        $cars = $this->car_model->fetchCars();

        $data['data'] = [
            'title' => 'Reservations',
            'message' => 'Welcome to the Reservations Creation page',
            'cars' => $cars
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
            //* Checks if the email already exists in the database, and if it does grabs their user_id to link to the reservation
            if ($this->user_model->emailExists($data['email'])) {
                $user = $this->user_model->findByEmail($data['email']);
                // if email exists, grab user_id
                $data['user_id'] = $user['user_id'];
            }

            //? Add reservation
            $reservation_id = $this->reservation_model->createAndGetId($data);

            //? Add car to reservation
            $this->reservation_model->addCarToReservation($data['cars_id'], $reservation_id);
            FlashMessage::success("Reservation added: You will get an email with your reservation details. Reservation Number: $reservation_id");
        } else {
            return $this->redirect($request, $response, 'reservations.create');
        }

        //TODO: FILL RESERVATION INFORMATION IN THE EMAIL
        $to = $data['email'];
        $subject = "Reservation Created";
        $message = "Solaf Performance has received your reservation request with reservation number: $reservation_id. You will get a response withing 24h.";
        try {
            sendMail($to, $subject, $message);
        } catch (Exception $e) {
            FlashMessage::error("Error sending email");
        }
        // dd(SessionManager::get('user_role'));
        if (SessionManager::get('user_role') === 'admin') {
            return $this->redirect($request, $response, 'reservations.index');
        } else {
            return $this->redirect($request, $response, 'customer.reservations');
        }
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
    //for the customer
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
        FlashMessage::success("Reservation updated: You will get an email with your reservation details");

        //TODO: FILL RESERVATION INFORMATION IN THE EMAIL
        $to = $data['email'];
        $subject = "Reservation Created";
        $message = "Solaf Performance has received the modifications of your request with reservation number: $reservation_id . You will get a response soon";

        try {
            sendMail($to, $subject, $message);
        } catch (Exception $e) {
            FlashMessage::error("Error sending email");
        }

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
        $end_time = $data['end_time'] ?? null;
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
            $end_time = null;
        } elseif ($reservation_type === 'hourly') {
            if (empty($end_time)) {
                $errors[] = "Must include an end time for hourly reservations";
            }
            $dropoff = null;
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

    private function cancelReservation(Request $request, Response $response, array $args): Response
    {
        $reservation_id = $args['reservation_id'];
        $this->reservation_model->cancelReservation($reservation_id);
        FlashMessage::success("Reservation Cancelled");

        $start_time = $args['start_time'];

        // TODO: NEED TO INCLUDE THE EMAIL FOR SOLAF PERFORMANCE AND ACTUALLY SEND THE EMAIL HERE
        $to = $args['email'];
        $subject = "Reservation Cancelled";
        $message = "Hello your reservation at $start_time has been Cancelled";

        try {
            sendMail($to, $subject, $message);
        } catch (Exception $e) {
            FlashMessage::error("Error sending email");
        }

        return $this->index($request, $response, $args);
    }

    public function view(Request $request, Response $response, array $args): Response
    {
        $reservation_id = $args['reservation_id'];

        // Fetch reservation
        $reservation = $this->reservation_model->fetchReservationById($reservation_id);

        // Fetch the car assigned to this reservation
        $car = $this->reservation_model->getCarForReservation($reservation_id);

        if (is_array($car) && isset($car[0])) {
            $car = $car[0];
        }

        // Fetch all cars for dropdown
        $cars = $this->car_model->fetchCars();

        // Pass everything to the view
        $data = [
            'title' => 'Reservation',
            'reservations' => $reservation,
            'car' => $car,
            'cars' => $cars
        ];

        // dd($car);
        return $this->render($response, '/admin/reservations/reservationEditView.php', $data);
    }
    private function approveReservation(int $reservation_id, array $data): bool
    {
        $price = $data['price'] ?? null;
        $reservation = $this->reservation_model->fetchReservationById($reservation_id);
        //$payment = $this->payment_model->fetchPaymentByID($reservation_id);

        if ($reservation['reservation_status'] == 'cancelled' || $reservation['reservation_status'] == 'refunded') {
            FlashMessage::warning("The Reservation is either already confirmed or refunded");
            return false;
        }

        // check if payment exists, it there is a set price and status is pending
        if ($this->payment_model->ifPaymentExists($reservation_id) == true && $reservation['reservation_status'] == 'pending') {
            $this->payment_model->updateTotalAmount($reservation_id, $price);
            $this->reservation_model->approveReservation($reservation_id);
            //send an email about price change
            $to = $reservation['email'];
            $subject = "Reservation Approval";
            $message = "Your reservation with Reservation Number $reservation_id has been approved. The current price is $price.";

            try {
                sendMail($to, $subject, $message);
            } catch (Exception $e) {
                FlashMessage::error("Error sending email");
            }

            FlashMessage::success("Reservation Approved, price changed to $price");
            return true;
        }

        if ($this->payment_model->ifPaymentExists($reservation_id) == false) {
            // normal approval flow, create new payment
            $this->reservation_model->approveReservation($reservation_id);
            $this->payment_model->createPayment($reservation_id, $price);

            $to = $reservation['email'];
            $subject = "Reservation Approval";
            $message = "Your reservation with Reservation Number $reservation_id has been approved. The current price is $price.";
            try {
                sendMail($to, $subject, $message);
            } catch (Exception $e) {
                FlashMessage::error("Error sending email");
            }

            FlashMessage::success("Reservation Approved");
            return true;
        }
        //if reservation is approved but the admin wants to change the price
        //TODO: bug where it sends email but doesnt set status to approved
        if ($this->payment_model->ifPaymentExists($reservation_id) == true && $reservation['reservation_status'] == 'approved') {
            $this->payment_model->updateTotalAmount($reservation_id, $price);
            $this->reservation_model->updateReservationStatus($reservation_id, 'approved');

            $to = $reservation['email'];
            $subject = "Reservation Approval";
            $message = "Your reservation with Reservation Number $reservation_id has been updated. The current price is $price.";
            try {
                sendMail($to, $subject, $message);
            } catch (Exception $e) {
                FlashMessage::error("Error sending email");
            }

            FlashMessage::success("Reservation Approved");
            return true;
        }
        return false;
    }

    private function denyReservation(int $reservation_id): bool
    {

        $reservation = $this->reservation_model->fetchReservationById($reservation_id);

        if ($reservation['reservation_status'] === 'denied') {
            FlashMessage::warning("This reservation is already denied.");
            return false;
        }
        $this->reservation_model->denyReservation($reservation_id);
        $to = $reservation['email'];
        $subject = "Reservation Denied";
        $message = "Your reservation with Reservation Number $reservation_id has been denied due to our unavailability. We will let you know if a slot opens up.";
        try {
            sendMail($to, $subject, $message);
        } catch (Exception $e) {
            FlashMessage::error("Error sending email");
        }

        FlashMessage::success("Reservation Denied");
        return true;
    }
    private function refundReservation(int $reservation_id): bool
    {
        $reservation = $this->reservation_model->fetchReservationById($reservation_id);
        $payment = $this->payment_model->fetchPaymentByID($reservation_id);

        // check if total_paid is not null(has been paid) and status is cancelled
        if ($payment['total_paid'] != null && $reservation['reservation_status'] == 'cancelled') {
            $this->payment_model->refundPayment($reservation_id);
            $this->reservation_model->updateReservationStatus($reservation_id, 'refunded');
            $this->payment_model->updatePaymentStatus($reservation_id, 'refunded');

            $to = $reservation['email'];
            $subject = "Reservation Refund";
            $message = "Your reservation with Reservation Number $reservation_id has been refunded. You should receive the amount in your account within 5-7 business days.";
            try {
                sendMail($to, $subject, $message);
            } catch (Exception $e) {
                FlashMessage::error("Error sending email");
            }

            FlashMessage::success("Payment Refunded");
            return true;
        }

        //if the reservation will happen but we need to refund
        if ($payment['total_paid'] > $payment['total_amount'] && $reservation['reservation_status'] == 'pending') {
            $this->payment_model->refundPayment($reservation_id);
            $this->reservation_model->approveReservation($reservation_id);
            $this->payment_model->updateTotalPaid($reservation_id, $payment['total_amount']);
            FlashMessage::success("Payment Refunded");
            //TODO: send email saying it will be refunded
            $price = $payment['total_amount'];
            $pricePaid = $payment['total_paid'];
            $to = $reservation['email'];
            $subject = "Reservation Refund";
            $message = "Your reservation with Reservation Number $reservation_id has been partially refunded. You should receive the amount in your account within 5-7 business days. Please note that the refunded amount is the difference between what was paid($pricePaid) and the total amount due($price).";
            try {
                sendMail($to, $subject, $message);
            } catch (Exception $e) {
                FlashMessage::error("Error sending email");
            }
            return true;
        } else {
            FlashMessage::warning("Cannot refund: Customer hasnt paid yet or reservation not cancelled");
            return false;
        }
    }

    //check the clicked button on the madal then call the right method
    public function submitReservation(Request $request, Response $response, array $args): Response
    {
        $reservationId = (int)$args['reservation_id'];
        $post = $request->getParsedBody();

        // approve button
        if (isset($post['approve'])) {
            $success = $this->approveReservation($reservationId, $post);

            if ($success) {
                // Redirect to index on success
                return $response->withHeader("Location", APP_ADMIN_URL . "/reservations")->withStatus(302);
            } else {
                // Stay on same page if failed
                return $response->withHeader("Location", APP_ADMIN_URL . "/reservations/view/" . $reservationId)->withStatus(302);
            }
        }

        // deny button clicked
        if (isset($post['deny'])) {
            $success = $this->denyReservation($reservationId);

            if ($success) {

                // Redirect to index on success
                return $response->withHeader("Location", APP_ADMIN_URL . "/reservations")->withStatus(302);
            } else {
                // Stay on same page if failed
                return $response->withHeader("Location", APP_ADMIN_URL . "/reservations/view/" . $reservationId)->withStatus(302);
            }
        }

        if (isset($post['refund'])) {
            $success = $this->refundReservation($reservationId, $post);

            if ($success) {

                // Redirect to index on success
                return $response->withHeader("Location", APP_ADMIN_URL . "/reservations")->withStatus(302);
            } else {
                // Stay on same page if failed
                return $response->withHeader("Location", APP_ADMIN_URL . "/reservations/view/" . $reservationId)->withStatus(302);
            }
        }
        // Default fallback
        return $response->withHeader("Location", APP_ADMIN_URL . "/reservations")->withStatus(302);
    }

    /**
     * Summary of guestShow
     * Handles the Guest Find Reservation functions from the reservation view
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function guestShow(Request $request, Response $response, array $args): Response
    {
        $info = $request->getParsedBody();
        SessionManager::set('user_role', 'guest');
        $guestEmail = $info['email'];
        $reservationId = $info['reservation_id'];

        $reservation = $this->reservation_model->fetchReservationGuest($guestEmail, $reservationId);
        $cars = $this->car_model->fetchCars();

        if ($reservation) {
            SessionManager::set('modify_mode', true);
            SessionManager::set('edit_reservation', $reservation[0]);
        }

        $data['data'] = [
            'title' => 'reservations',
            'reservations' => $reservation,
            'cars' => $cars,
            'modify_mode' => true,
            'edit_reservation' => $reservation[0] ?? null
        ];

        // dd($reservation);
        return $this->render($response, 'public/reservations/reservationsView.php', $data);
    }
    public function customerDetails(Request $request, Response $response, array $args): Response
    {
        $user_id = SessionManager::get('user_id');

        return $this->render($response, 'public/reservations/reservationsView.php');
    }
    /**
     * Summary of customerIndex
     * This loads the reservationsView
     * This page will load
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function customerIndex(Request $request, Response $response, array $args): Response
    {
        $user_id = SessionManager::get('user_id');

        $reservations = [];
        //if the suer is logged in
        if ($user_id !== null) {
            $reservations = $this->reservation_model->fetchAllCustomerReservations($user_id);
        } else {
            //if its a guest. fetch from the session
            $modify_mode = SessionManager::get('modify_mode') ?? false;
            $edit_reservation = SessionManager::get('edit_reservation') ?? null;

            if ($modify_mode === true && $edit_reservation !== null) {
                $reservations = [$edit_reservation];
            }
        }

        // Fetch all cars for the drop-down
        $cars = $this->car_model->fetchCars();


        $data = [
            'title' => 'reservations',
            'reservations' => $reservations,
            'cars' => $cars,
            'modify_mode' => SessionManager::get('modify_mode') ?? false,
            'edit_reservation' => SessionManager::get('edit_reservation') ?? null
        ];

        return $this->render($response, 'public/reservations/reservationsView.php', $data);
    }

    /**
     * Summary of customerDetails
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */


    public function editCustomerReservation(Request $request, Response $response, array $args): Response
    {
        //try fetching from the session manager in case they update the reservation and the page gets reloaded
        $reservationId = $args['reservation_id'] ?? SessionManager::get('last_reservation_id');
        $email = $args['email'] ?? SessionManager::get('last_reservation_email');


        //fetch the reservation by ID
        try {
            $reservation = $this->reservation_model->fetchReservationById($reservationId);
            //cehck if reservation wasnt found
            if (!$reservation) {
                FlashMessage::error("Reservation not found.");
                return $this->redirect($request, $response, 'customer.reservations');
            }
            //check if emails match
            if ($reservation['email'] !== $email) {
                FlashMessage::error("Email does not match this reservation.");
                return $this->redirect($request, $response, 'customer.reservations');
            }

            //in the db, the name of the column is total_amount, but we use 'price' alias in some places
            //check for both so that the price will be displayed correctly in the edit form
            if (!isset($reservation['price']) && isset($reservation['total_amount'])) {
                $reservation['price'] = $reservation['total_amount'];
            }

            //stpre the user info in session

            SessionManager::set("modify_mode", true);
            SessionManager::set("edit_reservation", $reservation);
            SessionManager::set("last_reservation_id", $reservationId);
            SessionManager::set("last_reservation_email", $email);

            return $this->redirect($request, $response, 'customer.reservations');
        } catch (\Exception $e) {
            error_log("Error in editCustomerReservation: " . $e->getMessage());
            FlashMessage::error("An error occurred. Please try again.");
            return $this->redirect($request, $response, 'customer.reservations');
        }
    }

    public function cancel(Request $request, Response $response, array $args): Response
    {
        $reservation_id = $args['reservation_id'];

        if (is_numeric($reservation_id)) {
            $this->reservation_model->cancelReservation($reservation_id);
            FlashMessage::error(hs(trans('flash.cancelled')));

            $updatedReservation = $this->reservation_model->fetchReservationById($reservation_id);

            $to = $updatedReservation['email'];
            $subject = "Reservation Cancelled";
            $message = "Solaf Performance has been notified that your reservation has been cancelled. If you have already paid for your reservation, we will review and refund you. If you do not receive an email within 5 days, contact us!";
            try {
                sendMail($to, $subject, $message);
            } catch (Exception $e) {
                FlashMessage::error("Error sending email");
            }
        }

        return $this->redirect($request, $response, 'customer.reservations');
    }


    public function updateCustomerReservation(Request $request, Response $response, array $args): Response
    {
        // Get form data
        $formData = $request->getParsedBody();
        $reservation_id = $args['reservation_id'];

        $errors = [];

        foreach (['pickup', 'dropoff', 'start_time', 'end_time', 'reservation_type'] as $field) {
            if (empty($formData[$field])) {
                $errors[] = "Please fill in " . ucfirst(str_replace('_', ' ', $field)) . ".";
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                FlashMessage::error($error);
                return $this->redirect($request, $response, 'customer.reservations');
            }
        }

        // If validation passes, update the reservation
        try {
            $reservationData = [
                'pickup' => $formData['pickup'],
                'dropoff' => $formData['dropoff'],
                'start_time' => $formData['start_time'],
                'end_time' => $formData['end_time'],
                'reservation_type' => $formData['reservation_type'],
                'cars_id' => $formData['cars_id']
            ];

            $this->reservation_model->updateCustomerReservation($reservation_id, $reservationData);

            FlashMessage::success(hs(trans('flash.update')));

            // reset modify_mode after saving
            SessionManager::set('modify_mode', false);
            SessionManager::remove('edit_reservation');

            return $this->redirect($request, $response, 'customer.reservations');
        } catch (\Exception $e) {
            // Display error message using FlashMessage::error()
            FlashMessage::error(hs(trans('flash.update_error')) . $e->getMessage());

            error_log($e->getTraceAsString());

            // Redirect back to 'customer.reservations' route
            return $this->redirect($request, $response, 'customer.reservations');
        }
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
    public function createCustomerReservation(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $errors = [];
        // dd($data);

        if ($this->validate($data)) {
            //* Checks if the email already exists in the database, and if it does grabs their user_id to link to the reservation
            if ($this->user_model->emailExists($data['email'])) {
                $user = $this->user_model->findByEmail($data['email']);
                // if email exists, grab user_id
                $data['user_id'] = $user['user_id'];
            }

            //? Add reservation
            $reservation_id = $this->reservation_model->createAndGetId($data);

            //? Add car to reservation
            $this->reservation_model->addCarToReservation($data['cars_id'], $reservation_id);
            FlashMessage::success(hs(trans('flash.create')));
        } else {
            return $this->redirect($request, $response, 'home.index');
        }

        //TODO: FILL RESERVATION INFORMATION IN THE EMAIL
        $to = $data['email'];
        $subject = "Reservation Pending";
        $message = "Solaf Performance has received your reservation request with reservation number: $reservation_id. You will get a response withing 24h.";
        try {
            sendMail($to, $subject, $message);
        } catch (Exception $e) {
            FlashMessage::error("Error sending email");
        }

        // dd(SessionManager::get('user_role'));
        return $this->redirect($request, $response, 'home.index');
    }
}
