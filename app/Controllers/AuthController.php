<?php

namespace App\Controllers;

use App\Domain\Models\ReservationModel;
use App\Domain\Models\UserModel;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Models\TwoFactorAuthModel;

class AuthController extends BaseController
{
    public function __construct(Container $container, private UserModel $userModel, private ReservationModel $reservationModel, private TwoFactorAuthModel $two_factor_auth_model)
    {
        parent::__construct($container);
    }

    public function validatePhoneNumber($phone) {}

    /**
     * Display the registration form (GET request).
     */
    public function register(Request $request, Response $response, array $args): Response
    {
        // Create a $data array with 'title' => 'Register'
        $data = [
            'title' => 'Register',
            'current_page' => 'register'
        ];

        // Render 'auth/register.php' view and pass $data
        return $this->render($response, 'auth/register.php', $data);
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
        ) {
            $errors[] = "Please fill in all fields.";
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email format: example@email.com";
        }

        // Check if email already exists using $this->userModel->emailExists($email)
        if ($this->userModel->emailExists($email)) {
            $user = $this->userModel->findByEmail($email);
            if ($user['role'] === 'customer') {
                $errors[] = "This email is already associated to an account.";
            }
        }

        // Validate phone number format
        $pattern = "/^(?:\d{3}[- ]\d{3}[- ]\d{4}|\(\d{3}\)[ ]?\d{3}[- ]\d{4}|\d{10})$/";
        if (!filter_var($phone, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => $pattern)))) {
            $errors[] = "Please enter a valid phone number format: 123-456-7890, 123 456 7890, (123) 456 7890";
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
            // Create $userData array with keys:
            $userData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'role' => 'customer'
            ];

            // Call $this->userModel->createUser($userData)
            if ($this->userModel->emailExists($email)) {
                $user = $this->userModel->findByEmail($email);
                // dd($this->userModel->updateUser($user['user_id'], $userData));
                $this->userModel->updateUser($user['user_id'], $userData);
            } else {
                $userId = $this->userModel->createUser($userData);
            }

            SessionManager::set('user_id', $userId);
            SessionManager::set('user_email', $email);
            // Display success message using FlashMessage::success()
            FlashMessage::success("Registration successful! Please log in.");

            // Redirect to 'auth.login' route
            // return $this->redirect($request, $response, 'auth.login');
            return $this->redirect($request, $response, '2fa.setup');
        } catch (\Exception $e) {
            // Display error message using FlashMessage::error()
            FlashMessage::error("Registration failed. Please try again.");

            // Redirect back to 'auth.register' route
            return $this->redirect($request, $response, 'auth.register');
        }
    }

    /**
     * Display the login form (GET request).
     */
    public function login(Request $request, Response $response, array $args): Response
    {
        // Create a $data array with 'title' => 'Login'
        $data = [
            'title' => 'Login',
            'current_page' => 'login'
        ];

        // Render 'auth/login.php' view and pass $data
        return $this->render($response, 'auth/login.php', $data);
    }

    /**
     * Process login form submission (POST request).
     */
    public function authenticate(Request $request, Response $response, array $args): Response
    {
        // Get form data using getParsedBody()
        $formData = $request->getParsedBody();

        // Extract 'identifier' and 'password' from form data
        $identifier = $formData['identifier'];
        $password = $formData['password'];

        // Start validation
        $errors = [];

        // Validate required fields (identifier and password)
        if (empty($identifier) || empty($password)) {
            $errors[] = "Please fill in all fields.";
        }

        // Check if $errors array is not empty
        if (!empty($errors)) {
            FlashMessage::error('Login unsuccessful');
            return $this->redirect($request, $response, 'auth.login');
        }

        // Attempt to verify user credentials
        $user = $this->userModel->verifyCredentials($identifier, $password);

        // Check if authentication was successful
        // If $user is null (authentication failed):
        if ($user == null) {
            FlashMessage::error("Invalid credentials. Please try again.");
            return $this->redirect($request, $response, 'auth.login');
        }

        // Authentication successful - create session
        // Store user data in session using SessionManager:
        // dd($user);
        SessionManager::set('user_id', $user['user_id']);
        SessionManager::set('user_email', $user['email']);
        SessionManager::set('user_phone', $user['phone']);
        SessionManager::set('user_name', $user['first_name'] . ' ' . $user['last_name']);
        SessionManager::set('user_role', $user['role']);
        SessionManager::set('is_authenticated', true);
        SessionManager::set('first_name', $user['first_name']);
        SessionManager::set('last_name', $user['last_name']);
        //2FA
        $has2FA = $this->two_factor_auth_model->isEnabled($user['user_id']);
        SessionManager::set('requires_2fa', $has2FA);
        SessionManager::set('two_factor_verified', !$has2FA);

        if ($has2FA == false) {
            return $this->redirect($request, $response, '2fa.setup');
        }

        // $reservations = $this->reservationModel->fetchReservationByUserID($user['user_id']);
        // dd($reservations);
        // SessionManager::set('reservations', $reservations);

        // Redirect based on role:
        if ($user['role'] === 'admin') {
            // Display success message using FlashMessage::success()
            FlashMessage::success("Welcome back, {$user['first_name']}!");
            return $this->redirect($request, $response, 'admin.dashboard');
        } else {
            // return $this->redirect($request, $response, 'user.dashboard');
            return $this->redirect($request, $response, 'home.index');
        }
    }

    /**
     * Logout the current user (GET request).
     */
    public function logout(Request $request, Response $response, array $args): Response
    {
        // Destroy the session
        SessionManager::destroy();

        //* Have to start new session here to pass on the successful logout message
        SessionManager::start();

        // Display success message
        FlashMessage::success("You have been logged out successfully!");

        // Redirect to 'home.index' route
        return $this->redirect($request, $response, 'home.index');
    }

    /**
     * Display user dashboard (protected route).
     */
    public function dashboard(Request $request, Response $response, array $args): Response
    {
        $userId = SessionManager::get('user_id');
        $has2FA = $this->two_factor_auth_model->isEnabled($userId);
        // Create a $data array with 'title' => 'Dashboard'
        $data = [
            'title' => 'Dashboard',
            'has2FA' => $has2FA
        ];

        // Render 'user/dashboard.php' view and pass $data
        return $this->render($response, 'user/dashboardView.php', $data);
    }

    /**
     * Summary of changePassword
     * Renders the change password screen
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function changePassword(Request $request, Response $response, array $args): Response
    {
        $data = [
            'title' => 'change-password',
        ];

        return $this->render($response, 'auth/resetPassword.php', $data);
    }

    public function saveNewPassword(Request $request, Response $response, array $args): Response
    {
        $formData = $request->getParsedBody();

        $userId = -1;
        //*Check if the user is logged in or they are using the forgot password link
        if (SessionManager::get('is_authenticated')) {
            $userId = SessionManager::get('user_id');
        } else {
            $user = $this->userModel->findByEmail($formData['email']);
            $userId = $user['user_id'];
        }

        $password = $formData['password'];
        $confirmPassword = $formData['confirmPassword'];

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

        if (!empty($errors)) {
            foreach ($errors as $error) {
                FlashMessage::error($error);
            }

            return $this->redirect($request, $response, 'auth/resetPassword.php');
        }

        // TODO: FINISH THIS PART
        $this->userModel->saveNewPassword($userId, $password);
        FlashMessage::success("Password changed");

        return $this->redirect($request, $response, 'auth.login');
    }
}
