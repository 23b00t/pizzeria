<?php

// INFO: Return types
// https://dev.to/karleb/return-types-in-php-3fip

require_once __DIR__ . '/Models/User.php';
require_once __DIR__ . '/Controllers/UserController.php';
require_once __DIR__ . '/Helpers/Helper.php';

/**
 * Router class for processing and handling HTTP requests.
 * 
 * This class checks the request type (GET or POST) and calls the corresponding 
 * method to handle the request. It automatically starts a session if none is active.
 *
 * Properties:
 * 
 * Methods:
 * 
 * @method void __construct() 
 *         Starts a session if one is not already active.
 * 
 * @method void handleRequest() 
 *         Checks the request method (POST/GET) and delegates 
 *         the handling to the appropriate method.
 * 
 * @method void handlePost() 
 *         Handles POST requests, specifically for login, 
 *         registration, and signout. Performs CSRF token validation.
 * 
 * @method void handleGet() 
 *         Handles GET requests, specifically for displaying 
 *         user information.
 */
class Router
{
    /**
     * Constructor that starts a session if none is active.
     */
    public function __construct()
    {
        // Check if a session is active, otherwise start one
        session_status() === PHP_SESSION_NONE && session_start();
    }

    /**
     * Handles the incoming request by checking its method and 
     * delegating to the appropriate handler method.
     * 
     * @return void
     */
    public function handleRequest(): void 
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->handlePost();
        } elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
            $this->handleGet();
        }
    }

    /**
     * Handles POST requests for login, registration, and signout.
     * Validates the CSRF token before processing.
     * 
     * @return void
     */
    private function handlePost(): void
    {
        if (isset($_POST['login'])) {
            Helper::checkCSRFToken();

            $userController = new UserController();
            $userController->login($_POST);

            exit();
        }

        if (isset($_POST['register'])) {
            Helper::checkCSRFToken();

            $userController = new UserController();
            $userController->create($_POST);

            exit();
        }

        isset($_POST["signout"]) && UserController::signOut();
    }

    /**
     * Handles GET requests, specifically for displaying user information.
     * Redirects to the login form if no user_id is provided.
     * 
     * @return void
     */
    private function handleGet(): void
    {
        if (isset($_GET['user_id'])) {
            // Instantiate UserController
            $userController = new UserController();

            // Retrieve user ID from the URL
            $userId = $_GET['user_id'];

            // Call the show method of UserController
            $userController->show($userId);
        } else {
            header('Location: ./Views/login_form.php');
        }
    }
}
