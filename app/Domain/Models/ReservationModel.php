<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

// TODO: ADD VALUES TO THE DATABASE TO TEST THE MODELS
class ReservationModel extends BaseModel
{
    public function __construct(PDOService $pdoservice, private PaymentModel $payment_model)
    {
        parent::__construct($pdoservice);
    }

    /**
     * Summary of fetchReservationByUserID
     * Fetches the list of reservation based on user ID.
     * This function is meant to be used for customers that are logged in to their account.
     * @param mixed $user_id
     * @return array
     */
    public function fetchReservationByUserID($user_id): mixed
    {
        $sql = "SELECT * FROM reservations WHERE user_id = $user_id";
        $reservations = $this->selectAll($sql);
        return $reservations;
    }

    /**
     * Summary of fetchReservationGuest
     * Fetches the reservation based on the user's email and the reservation number
     * This function is meant to be used for guest accounts that do not have a login to automatically
     * get their information from the database.
     * @param mixed $user_email
     * @param mixed $reservation_id
     * @return array|bool
     */
    public function fetchReservationGuest($user_email, $reservation_id): mixed
    {
        $sql = "SELECT * FROM reservations WHERE reservation_id = $reservation_id AND user_id = (SELECT user_id FROM users WHERE email = $user_email)";

        $reservation = $this->selectOne($sql);
        return $reservation;
    }

    /**
     * Summary of fetchReservations
     * Fetches all the reservations from the database in order of start_time
     * This function is meant to be used for the Admin to view all upcoming reservations
     * @return array
     */
    public function fetchReservationsOrderByTime(): mixed
    {
        $sql = "SELECT * FROM reservations ORDER BY start_time";
        $reservations = $this->selectAll($sql);
        return $reservations;
    }

    /**
     * Summary of fetchReservations
     * Fetches the list of reservations from the database
     * This function is meant to be used for the Admin page
     * @return array
     */
    public function fetchReservations(): mixed
    {
        $sql = "SELECT reservations.*, users.email FROM reservations
        JOIN users ON users.user_id = reservations.user_id";
        $reservations = $this->selectAll($sql);
        return $reservations;
    }

    public function fetchReservationById($reservation_id): mixed
    {
        $sql = "SELECT * FROM reservations WHERE reservation_id = :reservation_id";

        $reservation = $this->selectOne($sql, ['reservation_id' => $reservation_id]);
        return $reservation;
    }

    /**
     * Summary of createAndGetId
     * Creates a new reservation in the database
     * @param array $data
     * @return bool|string
     */
    public function createAndGetId(array $data): int
    {
        $sql = "INSERT INTO reservations (user_id, start_time, end_time, pickup, dropoff, comments, reservation_type, reservation_status) VALUES (:user_id, :start_time, :end_time, :pickup, :dropoff, :comments, :reservation_type, :reservation_status)";

        $this->execute($sql, ['user_id' => $data['user_id'], 'start_time' => $data['start_time'], 'end_time' => $data['end_time'], 'pickup' => $data['pickup'], 'dropoff' => $data['dropoff'], 'comments' => $data['comments'], 'reservation_type' => $data['reservation_type'], 'reservation_status' => 'pending']);

        $last_id = $this->pdo->lastInsertId();
        return $last_id;
    }

    /**
     * Summary of deleteReservation
     * Removes a reservation from the database
     * @param mixed $reservation_id
     * @return int
     */
    public function deleteReservation($reservation_id): int
    {
        $sql = "DELETE FROM reservations WHERE reservation_id = :reservation_id";

        return $this->execute($sql, ['reservation_id' => $reservation_id]);
    }

    /**
     * Summary of cancelReservation
     * Sets the status of a reservation to cancelled without deleting it from the database
     * @param mixed $reservation_id
     * @return int
     */
    public function cancelReservation($reservation_id): int
    {
        $sql = "UPDATE reservations
                SET reservation_status = cancelled
                WHERE reservation_id = :reservation_id";
        return $this->execute($sql, ['reservation_id' => $reservation_id]);
    }

    /**
     * Summary of approveReservation
     * Sets the status of a reservation to approved
     * @param mixed $reservation_id
     * @return int
     */
    public function approveReservation($reservation_id): int
    {
        $sql = "UPDATE reservations
                SET reservation_status = approved
                WHERE reservation_id = :reservation_id";
        return $this->execute($sql, ['reservation_id' => $reservation_id]);
    }

    /**
     * Summary of denyReservation
     * Sets the status of a reservation to denied
     * @param mixed $reservation_id
     * @return int
     */
    public function denyReservation($reservation_id): int
    {
        $sql = "UPDATE reservations
                SET reservation_status = denied
                WHERE reservation_id = :reservation_id";

        // Call the payment model to edit the payment information
        $this->payment_model->denyPayment($reservation_id);

        return $this->execute($sql, ['reservation_id' => $reservation_id]);
    }

    /**
     * Summary of completeReservation
     * Sets he status of a reservation to completed.
     * This is to be used automatically a day after the reservation date.
     * //*Might be better to make the admin set to completed manually
     * @param mixed $reservation_id
     * @return int
     */
    public function completeReservation($reservation_id): int
    {
        $sql = "UPDATE reservations
                SET reservation_status = completed
                WHERE reservation_id = :reservation_id";
        return $this->execute($sql, ['reservation_id' => $reservation_id]);
    }

    /**
     * Summary of updateReservation
     *
     * @param mixed $reservation_id
     * @param mixed $data
     * @return int
     */
    public function updateReservation($reservation_id, $data): int
    {
        $sql = "UPDATE reservations
                SET start_time = :start_time,
                end_time = :end_time,
                pickup = :pickup,
                dropoff = :dropoff,
                comments = :comments
                reservation_type = :reservation_type,
                reservation_status = pending";

        return $this->execute($sql, ['start_time' => $data['start_time'], 'end_time' => $data['end_time'], 'pickup' => $data['pickup'], 'dropoff' => $data['dropoff'], 'comments' => $data['comments'], 'reservation_type' => $data['reservation_type']]);
    }
}
