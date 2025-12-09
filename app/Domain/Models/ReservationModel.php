<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

// TODO: ADD VALUES TO THE DATABASE TO TEST THE MODELS
class ReservationModel extends BaseModel
{
    public function __construct(PDOService $pdoservice, private PaymentModel $payment_model, private UserModel $user_model)
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
        // $sql = "SELECT * FROM reservations WHERE user_id = :user_id";

        $sql = "SELECT
            reservations.*,
            users.first_name,
            users.last_name,
            users.email,
            users.phone,
            users.user_id,
            payments.total_amount as price,
            payments.total_paid as total_paid
        FROM reservations
        JOIN users ON users.user_id = reservations.user_id
        LEFT JOIN payments ON payments.reservation_id = reservations.reservation_id
        WHERE users.user_id = :user_id";

        $reservations = $this->selectAll($sql, ['user_id' => $user_id]);
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
        // $sql = "SELECT * FROM reservations WHERE reservation_id = :reservation_id AND user_id = (SELECT user_id FROM users WHERE email = :user_email)";

        $user = $this->user_model->findByEmail($user_email);
        $sql = "SELECT
                r.*,
                u.user_id,
                u.first_name,
                u.last_name,
                u.email,
                u.phone,
                p.total_amount,
                p.payment_status,
                p.total_amount,
                c.cars_id,
                c.brand,
                c.model,
                c.year
                    FROM reservations r
                    JOIN users u ON u.user_id = r.user_id
                    LEFT JOIN reservation_cars rc ON r.reservation_id = rc.reservation_id
                    LEFT JOIN cars c ON c.cars_id = rc.cars_id
                    LEFT JOIN payments p ON r.reservation_id = p.reservation_id
                WHERE r.reservation_id = :reservation_id
                AND u.user_id = (SELECT u.user_id FROM users WHERE email = :user_email)";

        $reservation = $this->selectAll($sql, ['reservation_id' => $reservation_id, 'user_email' => $user_email]);
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
        $sql = <<<SQL
            SELECT
                r.*,
                u.email,
                rc.cars_id AS selected_car_id,
                c.cars_id AS car_id,
                c.brand,
                c.model,
                c.year
            FROM reservations r
            JOIN users u ON u.user_id = r.user_id
            LEFT JOIN reservation_cars rc ON r.reservation_id = rc.reservation_id
            LEFT JOIN cars c ON c.cars_id = rc.cars_id
        SQL;
        $reservations = $this->selectAll($sql);
        return $reservations;
    }

    public function fetchReservationById($reservation_id): mixed
    {
        $sql = "SELECT
            reservations.*,
            users.first_name,
            users.last_name,
            users.email,
            users.phone,
            payments.total_amount as price,
            payments.total_paid as total_paid
        FROM reservations
        JOIN users ON users.user_id = reservations.user_id
        LEFT JOIN payments ON payments.reservation_id = reservations.reservation_id
        WHERE reservations.reservation_id = :reservation_id";

        $reservation = $this->selectOne($sql, ['reservation_id' => $reservation_id]);
        return $reservation;
    }

    public function fetchAllCustomerReservations($user_id): mixed
    {
        $sql = <<<sql
            SELECT
                r.*,
                u.first_name,
                u.last_name,
                u.email,
                u.phone,
                p.total_amount,
                p.payment_status,
                p.total_amount,
                c.cars_id,
                c.brand,
                c.model,
                c.year,
                ci.image_id,
                ci.image_path
                    FROM reservations r
                    JOIN users u ON u.user_id = r.user_id
                    LEFT JOIN reservation_cars rc ON r.reservation_id = rc.reservation_id
                    LEFT JOIN cars c ON c.cars_id = rc.cars_id
                    LEFT JOIN car_images ci ON c.cars_id = ci.cars_id
                    LEFT JOIN payments p ON r.reservation_id = p.reservation_id
                WHERE r.user_id = :user_id
        sql;

        $reservations = $this->selectAll($sql, ['user_id' => $user_id]);
        return $reservations;
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

        // $end_time = $data['end_time'] ?? null

        if (empty($data['end_time'])) {

            $data['end_time'] = null;
        }

        $this->execute($sql, ['user_id' => $data['user_id'], 'start_time' => $data['start_time'], 'end_time' => $data['end_time'], 'pickup' => $data['pickup'], 'dropoff' => $data['dropoff'], 'comments' => $data['comments'], 'reservation_type' => $data['reservation_type'], 'reservation_status' => 'pending']);

        $last_id = $this->pdo->lastInsertId();
        return $last_id;
    }

    /**
     * Summary of addCarToReservation
     * Creates a new reservation_cars in the database
     * @param array $data
     * @return bool|string
     */
    public function addCarToReservation(int $cars_id, int $reservation_id)
    {
        $sql = "INSERT INTO reservation_cars (cars_id, reservation_id) VALUES (:cars_id, :reservation_id)";

        return $this->execute($sql, ['cars_id' => $cars_id, 'reservation_id' => $reservation_id]);
    }

    /**
     * Summary of getCarForReservation
     * Selects cars in the database
     * @param array $data
     * @return bool|string
     */
    public function getCarForReservation(int $reservation_id)
    {
        $sql = <<<sql
            SELECT c.*
            FROM cars c
            INNER JOIN reservation_cars rc ON rc.cars_id = c.cars_id
            WHERE rc.reservation_id = :reservation_id
        sql;

        return $this->selectAll($sql, ['reservation_id' => $reservation_id]);
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
        $sql = <<<sql
            UPDATE reservations
                SET reservation_status = "cancelled"
                WHERE reservation_id = :reservation_id
        sql;
        return $this->execute($sql, ['reservation_id' => $reservation_id]);
    }

    /**
     * Summary of approveReservation
     * Sets the status of a reservation to approved
     * @param mixed $reservation_id
     * @return int
     */
    public function approveReservation($reservation_id)
    {
        $sql = "UPDATE reservations
            SET reservation_status = 'approved'
            WHERE reservation_id = :reservation_id";
        return $this->execute($sql, [
            'reservation_id' => $reservation_id
        ]);
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
                SET reservation_status = 'denied'
                WHERE reservation_id = :reservation_id";

        return $this->execute($sql, [
            'reservation_id' => $reservation_id
        ]);
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
    public function updateReservationStatus($reservation_id, $status): int
    {
        $sql = "UPDATE reservations
    SET reservation_status = :status
    WHERE reservation_id = :reservation_id";

        return $this->execute($sql, [
            'status' => $status,
            'reservation_id' => $reservation_id
        ]);
    }

    public function updateCustomerReservation($reservation_id, array $data)
    {
        // Start the tran to update both tables
        $this->beginTransaction();

        try {
            // Update the reservation
            $sqlReservation = <<<sql
                UPDATE reservations
                SET
                    pickup = :pickup,
                    dropoff = :dropoff,
                    start_time = :start_time,
                    end_time = :end_time,
                    reservation_type = :reservation_type
                WHERE reservation_id = :reservation_id
            sql;

            $count = $this->execute($sqlReservation, [
                'pickup' => $data['pickup'],
                'dropoff' => $data['dropoff'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'reservation_type' => $data['reservation_type'],
                'reservation_id' => $reservation_id
            ]);

            // Don't throw exception if no rows are affected
            if ($count === false) {
                throw new \Exception("Database error");
            }

            if (isset($data['cars_id'])) {
                // Update the car
                $sqlCar = <<<sql
                    UPDATE reservation_cars
                    SET cars_id = :cars_id
                    WHERE reservation_id = :reservation_id
                sql;


                $count = $this->execute($sqlCar, [
                    'cars_id' => $data['cars_id'],
                    'reservation_id' => $reservation_id
                ]);

                // Don't throw exeption if no rows are affected
                if ($count === false) {
                    throw new \Exception("Database error");
                }
            }

            // Commit the transaction
            $this->commit();

            return true;
        } catch (\Exception $e) {
            // Rollback if it doesn't work
            $this->rollback();
            throw $e;
        }
    }
}
