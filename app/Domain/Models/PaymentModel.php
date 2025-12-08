<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class PaymentModel extends BaseModel
{
    public function __construct(PDOService $pdoservice)
    {
        parent::__construct($pdoservice);
    }

    /**
     * Summary of fetchPaymentByID
     * Fetches the payment(receipt) information from the database
     * Used for either the Admin or the Customer to see how much is still owed for their reservation
     * @param mixed $reservation_id
     * @return array|bool
     */
    public function fetchPaymentByID($reservation_id)
    {
        $sql = "SELECT * FROM payments WHERE reservation_id = :reservation_id";
        $payment = $this->selectOne($sql, ['reservation_id' => $reservation_id]);
        return $payment;
    }

    /**
     * Summary of create
     * Creates a payment in the database and returns the id
     * of the newly created payment
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function createAndGetId(array $data): int
    {
        // todo: Do this
        $sql = "INSERT INTO payments (reservation_id, total_amount, payment_status) VALUES (:reservation_id, :total_amount, pending)";

        $this->execute($sql, ['reservation_id' => $data['reservation_id'], 'total_amount' => $data['total_amount']]);

        $last_id = $this->pdo->lastInsertId();
        return $last_id;
    }

    /**
     * Summary of payPayment
     * Sets the status of a payment to paid. To be used if the
     * customer decides to pay online rather than in person
     * @param mixed $payment_id
     * @return int
     */
    public function createPayment($reservation_id, $totalAmount)
    {
        $sql = "INSERT INTO payments (reservation_id, total_amount, payment_status) VALUES (:reservation_id, :total_amount, :payment_status)";
        return $this->execute($sql, [
            'reservation_id' => $reservation_id,
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
        ]);
    }
    public function payPayment($reservation_id): int
    {
        $sql = "UPDATE payments
        SET payment_status = 'paid',
        paid_at = current_timestamp()
        WHERE reservation_id = :reservation_id";

        return $this->execute($sql, ['reservation_id' => $reservation_id]);
    }

    /**
     * Summary of denyPayment
     * Sets the status of a payment to denied
     * Used if the admin denies the reservation
     * @param mixed $reservation_id
     * @return int
     */
    public function denyPayment($reservation_id): int
    {
        $sql = "UPDATE payments
                SET payment_status = :payment_status";

        return $this->execute($sql, ['payment_status' => 'denied']);
    }

    /**
     * Summary of updatePayment
     * Updates a payment in the database
     * @param mixed $reservation_id
     * @param mixed $data
     * @return int
     */
    public function updatePayment($reservation_id, $data): int
    {
        $sql = "UPDATE payments
                SET total_amount = :total_amount,
                payment_status = :payment_status";

        return $this->execute($sql, ['total_amount' => $data['total_amount'], 'payment_status' => $data['payment_status']]);
    }

    /**
     * Summary of getPaymentStatus
     * Fetches a single payment status based on the reservation_id
     * @param mixed $reservation_id
     * @return int
     */
    public function getPaymentStatus($reservation_id): mixed
    {
        $sql = "SELECT payment_status FROM payments WHERE reservation_id = :reservation_id";

        return $this->execute($sql, ['reservation_id' => $reservation_id]);
    }
    public function fetchTotalAMount($reservation_id): mixed
    {
        $sql = "SELECT total_amount FROM payments WHERE reservation_id = :reservation_id";

        return $this->execute($sql, ['reservation_id' => $reservation_id]);
    }

    public function refundPayment($reservation_id): int
    {
        $sql = "UPDATE payments
        SET payment_status = 'refunded'
        WHERE reservation_id = :reservation_id";

        return $this->execute($sql, ['reservation_id' => $reservation_id]);
    }

    public function updateTotalAmount($reservation_id, $new_total): int
    {
        $sql = "UPDATE payments
        SET total_amount = :total_amount
        WHERE reservation_id = :reservation_id";

        return $this->execute($sql, ['total_amount' => $new_total, 'reservation_id' => $reservation_id]);
    }

    public function updateTotalPaid($reservation_id, $new_total): int
    {
        $sql = "UPDATE payments
        SET total_paid = :total_paid
        WHERE reservation_id = :reservation_id";

        return $this->execute($sql, ['total_paid' => $new_total, 'reservation_id' => $reservation_id]);
    }

    public function updatePaymentStatus($reservation_id, $new_status): int
    {
        $sql = "UPDATE payments
        SET payment_status = :payment_status
        WHERE reservation_id = :reservation_id";

        return $this->execute($sql, ['payment_status' => $new_status, 'reservation_id' => $reservation_id]);
    }
    /**
     * Summary of getBalance
     * Gets the information to calculate balance in the controller
     * @param mixed $reservation_id
     * @return int
     */
    public function getBalance($reservation_id): mixed
    {
        //TODO: check this once Sona sends me the up to date database
        $sql = "SELECT total_amount, total_paid FROM payments WHERE reservation_id = :reservation_id";

        return $this->selectOne($sql, ['reservation_id' => $reservation_id]);
    }

    public function ifPaymentExists($reservation_id): bool
    {
        $sql = "SELECT COUNT(*) as count FROM payments WHERE reservation_id = :reservation_id";
        $result = $this->selectOne($sql, ['reservation_id' => $reservation_id]);
        if ($result['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }
}
