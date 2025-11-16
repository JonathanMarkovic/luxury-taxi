<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\UserModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends BaseController {
    public function __construct(Container $container, private UserModel $userModel)
    {
        parent::__construct($container);
    }

    /**
     * Summary of index
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return Response
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $faq = $this->userModel->fetchUsers();

        $data['data'] = [
            "title" => "Admin Categories",
            'categories' => $faq
        ];

        return $this->render($response, 'admin/customers/customerIndexView.php', $data);
    }

    /**
     * Summary of edit
     * Function used to edit a user
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return Response
     */
    public function edit(Request $request, Response $response, array $args): Response {
        $user_id = $args['user_id'];

        $user = $this->userModel->fetchUserById($user_id);

        $data['data'] = [
            'title' => 'Users',
            'message' => 'Welcome to the user details page',
            'user' => $user
        ];

        return $this->render(
            $response,
             //TODO:  Enter the view path here,
             $data);
    }

    public function update(Request $request, Response $response, array $args): Response {
        $user_id = $args['user_id'];

        $data = $request->getParsedBody();

        $this->userModel->updateUser($user_id, $data);

        return $this->redirect($request, $response, 'users.index');
    }
}
