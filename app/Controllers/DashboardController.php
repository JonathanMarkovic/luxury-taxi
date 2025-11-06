<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DashboardController extends BaseController
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * Summary of index
     * The index page for the admin dashboard.
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return float|int
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
        ];

        return $this->render(
            $response,
            '/admin/dashboardView.php',
            $data
        );
    }
}
