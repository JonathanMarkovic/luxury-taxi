<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\PaymentModel;
use App\Helpers\FlashMessage;
use Core\Logger\ConsoleLogger;
use DI\Container;
use Exception;
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
        $reservation_id = $args['reservation_id'];
        $balanceInfo = $this->payment_model->getBalance($reservation_id);
        //* This balance will not be in cents because this is the displayed balance for the user
        // dd($balanceInfo);
        $balance = ($balanceInfo['total_amount'] - $balanceInfo['total_paid']);

        $square = new SquareClient(
            token: SQUARE_SANDBOX_ACCESS_TOKEN,
            // token: SQUARE_PRODUCTION_ACCESS_TOKEN,
            options: [
                'baseUrl' => Environments::Sandbox->value // Used by default
                // 'baseUrl' => Environments::Production->value
            ]
        );
        try {
            $locations = $square->locations->list();
            // dd($response);
        } catch (SquareException $e) {
            echo 'Square API Exception occurred: ' . $e->getMessage() . "\n";
            echo 'Status Code: ' . $e->getCode() . "\n";
        }

        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
            // 'payment' => $payment
            'locations' => $locations ?? [],
            'balance' => $balance,
            // 'balance' => 150.00
            'reservation_id' => $reservation_id
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
            token: SQUARE_SANDBOX_ACCESS_TOKEN,
            // token: SQUARE_PRODUCTION_ACCESS_TOKEN,
            options: [
                'baseUrl' => Environments::Sandbox->value // Used by default
                // 'baseUrl' => Environments::Production->value
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
        try {
            //* getting information to calculate the balance to be paid
            $reservation_id = $args['reservation_id'];

            $balanceInfo = $this->payment_model->getBalance($reservation_id);
            //* x100 the balance because square takes the amount in cents
            $balance = ($balanceInfo['total_amount'] - $balanceInfo['total_paid']) * 100;

            $data = json_decode(file_get_contents('php://input'), true);

            $square = new SquareClient(
                token: SQUARE_SANDBOX_ACCESS_TOKEN,
                // token: SQUARE_PRODUCTION_ACCESS_TOKEN,
                options: [
                    'baseUrl' => Environments::Sandbox->value // Used by default
                    // 'baseUrl' => Environments::Production->value
                ]
            );

            // Build amount: 1.00 USD
            // todo get info from payments table
            $amountMoney = new Money([
                'amount'   => $balance, // cents
                'currency' => Currency::Cad->value,
            ]);

            $squareRequest = new CreatePaymentRequest([
                'sourceId'       => $data['sourceId'],
                'idempotencyKey' => $data['idempotencyKey'],
                'amountMoney' => $amountMoney
                // optionally: 'locationId' => $data['locationId'],
            ]);

            //* this does the actual payment
            $squareResponse = $square->payments->create(request: $squareRequest);

            //* Get payment result and set FlashMessage
            $payment = $squareResponse->getPayment();
            $status = $payment?->getStatus();


            $payload = $squareResponse->jsonSerialize();
            if ($status === 'COMPLETED') {
                FlashMessage::success(hs(trans('flash.payment_success')));
                // FlashMessage::success($payment);
                // FlashMessage::success($status);
                $payload['redirect_to'] = RouteContext::fromRequest($request)
                    ->getRouteParser()
                    ->urlFor('customer.reservations');

                $this->payment_model->payPayment($reservation_id);
            } else {
                FlashMessage::error(hs(trans('flash.payment_failed')));
                $payload['redirect_to'] = RouteContext::fromRequest($request)
                    ->getRouteParser()
                    ->urlFor('user.payment');
            }

            // header('Content-Type: application/json');
            // echo json_encode($squareResponse->jsonSerialize());

            $response->getBody()->write(json_encode($payload));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $error = ['error' => $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}
