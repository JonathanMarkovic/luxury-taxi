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
        $sql = "SELECT * FROM payments WHERE reservation_id = $reservation_id";
        $payment = $this->selectOne($sql);
        return $payment;
    }

    /**
     * Summary of create
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function createAndGetId(Request $request, Response $response, array $args): int
    {
        // todo: Do this
    }
}
