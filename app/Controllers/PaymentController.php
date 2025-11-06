<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\PaymentModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PaymentController extends BaseController
{
    public function __construct(Container $container, private PaymentModel $payment_model)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $payment = $this->payment_model->fetchPaymentByID();
        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
            'payment' => $payment
        ];

        return $this - render(
            $response,
            //!Enter the view file name here ,
            $data
        );
    }
}
