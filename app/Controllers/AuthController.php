<?php

namespace App\Controllers;

use App\Domain\Models\UserModel;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function __construct(Container $container, private UserModel $userModel)
    {
        parent::__construct($container);
    }

    public function validatePhoneNumber($phone) {

    }

    /**
     * Display the registration form (GET request).
     */
    public function register(Request $request, Response $response, array $args): Response
    {
        // Create a $data array with 'title' => 'Register'
        $data = [
            'title' => 'Register'
        ];

        // Render 'auth/register.php' view and pass $data
        return $this->render($response, 'auth/register.php');
    }

    /**
     * Process registration form submission (POST request).
     */
    public function store(Request $request, Response $response, array $args): Response
    {
        // Get form data using getParsedBody()
        $formData = $request->getParsedBody();

        // Extract individual fields from $formData:
        $firstName = $formData['first_name'];
        $lastName = $formData['last_name'];
        $email = $formData['email'];
        $phone = $formData['phone'];
        $password = $formData['password'];
        $confirmPassword = $formData['confirm_password'];
        $role = $formData['role'];

        // Start validation
        $errors = [];

        // Validate required fields (first_name, last_name, phone, email, password, confirm_password)
        if (
            empty($firstName) ||
            empty($lastName) ||
            empty($email) ||
            empty($phone) ||
            empty($password) ||
            empty($confirmPassword)
        )
        {
            $errors[] = "Please fill in all fields.";
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email format: example@email.com";
        }

        // Check if email already exists using $this->userModel->emailExists($email)
        if ($this->userModel->emailExists($email)) {
            $errors[] = "This email is already associated to an account.";
        }

        // Validate phone number format
        $pattern = '/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/';
        if (!filter_var($phone, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => $pattern)))) {
            $errors[] ="Please enter a valid phone number format: 123-456-7890, 123 456 7890, (123) 456 7890";
        }

        // Validate password length (minimum 8 characters)
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }

        // Validate password contains at least one number
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number.";
        }

        // Check if password matches confirm_password
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }

        // If validation errors exist, redirect back with error message
        // Check if $errors array is not empty
        // If errors exist:
        //   - Use FlashMessage::error() with the first error message
        //   - Redirect back to 'auth.register' route
        if (!empty($errors)) {
            foreach ($errors as $error) {
                FlashMessage::error($error);
            }

            return $this->redirect($request, $response, 'auth.register');
        }

        // If validation passes, create the user
        try {
            // TODO: Create $userData array with keys:
            //       'first_name', 'last_name', 'username', 'email', 'password', 'role'

            // TODO: Call $this->userModel->createUser($userData)
            //       Store the returned user ID in $userId

            // TODO: Display success message using FlashMessage::success()
            //       Message: "Registration successful! Please log in."

            // TODO: Redirect to 'auth.login' route

        } catch (\Exception $e) {
            // TODO: Display error message using FlashMessage::error()
            //       Message: "Registration failed. Please try again."

            // TODO: Redirect back to 'auth.register' route
        }
    }
}
