<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\PaymentModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Square\SquareClient;
use Square\Environments;
use Square\Exceptions\SquareException;
use Square\Types\Currency;
use Square\Types\Money;
use Square\Payments\Requests\CreatePaymentRequest;


class PaymentController extends BaseController
{
    public function __construct(Container $container, private PaymentModel $payment_model)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        // $payment = $this->payment_model->fetchPaymentByID();


        $square = new SquareClient(
            token: SQUARE_ACCESS_TOKEN,
            options: [
                'baseUrl' => Environments::Sandbox->value // Used by default
            ]
        );
        try {
            $locations[] = $square->locations->list();
            // dd($response);
        } catch (SquareException $e) {
            echo 'Square API Exception occurred: ' . $e->getMessage() . "\n";
            echo 'Status Code: ' . $e->getCode() . "\n";
        }

        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
            // 'payment' => $payment
            'locations' => $locations
        ];

        return $this->render(
            $response,
            'public/payments/paymentView.php',
            $data
        );
    }

    // This is from the Square quickstart guide to test api connection
    public function showLocations(Request $request, Response $response, array $args)
    {
        $square = new SquareClient(
            token: SQUARE_ACCESS_TOKEN,
            options: [
                'baseUrl' => Environments::Sandbox->value // Used by default
            ]
        );

        try {
            $response = $square->locations->list();
            foreach ($response->getLocations() as $location) {
                printf(
                    "%s: %s, %s, %s<p/>",
                    $location->getId(),
                    $location->getName(),
                    $location->getAddress()?->getAddressLine1(),
                    $location->getAddress()?->getLocality()
                );
            }
        } catch (SquareException $e) {
            echo 'Square API Exception occurred: ' . $e->getMessage() . "\n";
            echo 'Status Code: ' . $e->getCode() . "\n";
        }

        $this->index($request,  $response,  $args);
    }

    public function pay(Request $request, Response $response, array $args): Response
    {
        $data = json_decode(file_get_contents('php://input'), true);



        $square = new SquareClient(
            token: SQUARE_ACCESS_TOKEN,
            options: [
                'baseUrl' => Environments::Sandbox->value // Used by default
            ]
        );

        //* getting information to calculate the balance to be paid
        // $reservation_id = $args['reservation_id'];
        // $balanceInfo = $this->payment_model->getBalance($reservation_id);
        // $balance = ($balanceInfo['total_amount'] - $balanceInfo['amount_paid']) * 100;

        // Build amount: 1.00 USD
        // todo get info from payments table
        $amountMoney = new Money([
            'amount'   => 1000, // cents
            'currency' => Currency::Cad->value,
        ]);

        $squareRequest = new CreatePaymentRequest([
            'sourceId'       => $data['sourceId'],
            'idempotencyKey' => $data['idempotencyKey'],
            'amountMoney'    => $amountMoney,
            // optionally: 'locationId' => $data['locationId'],
        ]);

        $squareResponse = $square->payments->create(request: $squareRequest);

        // header('Content-Type: application/json');
        // echo json_encode($squareResponse->jsonSerialize());
        $payload = $squareResponse->jsonSerialize();
        $payload['redirect_to'] = RouteContext::fromRequest($request)
            ->getRouteParser()
            ->urlFor('reservations.index');

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
