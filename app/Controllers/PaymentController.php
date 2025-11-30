<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\PaymentModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Square\SquareClient;
use Square\Environments;
use Square\Exceptions\SquareException;

class PaymentController extends BaseController
{
    public function __construct(Container $container, private PaymentModel $payment_model)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        // $payment = $this->payment_model->fetchPaymentByID();
        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
            // 'payment' => $payment
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
        // $config = require APP_BASE_DIR_PATH . '/config/env.php';

        // dd(SQUARE_ACCESS_TOKEN);

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

        // return $this->render(
        //     $response,
        //     'public/payments/paymentView.php'
        // );

        $this->index($request,  $response,  $args);
    }
}
