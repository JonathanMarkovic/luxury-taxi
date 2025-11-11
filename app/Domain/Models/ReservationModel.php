<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

// TODO: ADD VALUES TO THE DATABASE TO TEST THE MODELS
class ReservationModel extends BaseModel
{
    public function __construct(PDOService $pdoservice)
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
        $sql = "SELECT * FROM reservations";
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
     * Undocumented function
     * Updates a reservation
     * @param [type] $reservation_id
     * @param [type] $data
     * @return integer
     */
    public function updateReservation($reservation_id, $data): int
    {
        $sql = "UPDATE reservations
        SET start_time = :start_time, end_tme = :end_time, pickup = :pickup, dropoff = :dropoff, comments = :comments, reservation_status = 'pending'
        WHERE reservation_id = :reservation_id";

        return $this->execute($sql, [
            'reservation_id' => $reservation_id,
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'pickup' => $data['pickup'],
            'dropoff' => $data['dropoff'],
            'comments' => $data['comments']
        ]);
    }

    public function createReservation() {}
}
