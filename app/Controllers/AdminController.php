<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminController extends BaseController {
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
        ];

        return $this-render($response,
        //!Enter the view file name here ,
          $data);
    }
}
