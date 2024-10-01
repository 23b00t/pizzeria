<?php

// INFO: Return types
// https://dev.to/karleb/return-types-in-php-3fip

require_once __DIR__ . '/Models/User.php';
require_once __DIR__ . '/Controllers/UserController.php';
require_once __DIR__ . '/Controllers/PizzaController.php';
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
    public function handleRequest(string $uri): void 
    {

// file_put_contents('/opt/lampp/logs/custom_log', "handleRequest: " . print_r($uri, true), FILE_APPEND);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->handlePost($uri);
        } elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
            $this->handleGet($uri);
        }
    }

    /**
     * Handles POST requests for login, registration, and signout.
     * Validates the CSRF token before processing.
     * 
     * @return void
     */
    private function handlePost($uri): void
    {
        // User routes
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

        // Pizza routes
        // Store Pizza Route (Pizza erstellen)
        if ($uri === 'Pizza/store') {
            Helper::checkCSRFToken();
            $pizzaController = new PizzaController();
            $pizzaController->store($_POST);  // Übergabe der Formulardaten
            exit();
        }

        // Update Pizza Route (Pizza aktualisieren)
        if (preg_match('/Pizza\/update\/(\d+)$/', $uri, $matches)) {
            Helper::checkCSRFToken();
            $pizzaId = $matches[1];
            $pizzaController = new PizzaController();
            $pizzaController->update($pizzaId, $_POST);  // Übergabe der Formulardaten
            exit();
        }
    }

    /**
     * Handles GET requests, specifically for displaying user information.
     * Redirects to the login form if no user_id is provided.
     * 
     * @return void
     */

    private function handleGet(string $uri): void
    {
        // To avoid missmatches of the preg_match statement
        $uri === '' && header('Location: ./Views/login_form.php') && exit();

        switch ($uri) {
            // Pizza routes
            case 'Pizza/index': 
                $pizzaController = new PizzaController();
                $pizzaController->index(); 
                break;

            case (preg_match('/Pizza\/show\/(\d+)$/', $uri, $matches) && !empty($matches[1])):
                // file_put_contents('/opt/lampp/logs/custom_log', "match: " . print_r($matches, true), FILE_APPEND);
                $pizzaId = $matches[1];
                $pizzaController = new PizzaController();
                $pizzaController->show($pizzaId);
                break;

            case (preg_match('/Pizza\/edit\/(\d+)$/', $uri, $matches) && !empty($matches[1])):
                $pizzaId = $matches[1];
                $pizzaController = new PizzaController();
                $pizzaController->edit($pizzaId);
                break;

            case 'Pizza/create':
                $pizzaController = new PizzaController();
                $pizzaController->create();
                break;

            case (preg_match('/Pizza\/delete\/(\d+)$/', $uri, $matches) && !empty($matches[1])):
                $pizzaId = $matches[1];
                $pizzaController = new PizzaController();
                $pizzaController->delete($pizzaId);
                break;
        
            // User route
            case (preg_match('/user_id=(\d+)$/', $uri, $matches) && !empty($matches[1])):
                $userId = $matches[1];
                $userController = new UserController();
                $userController->show($userId); 
                break;

            default:
                header('Location: ./Views/login_form.php');
                break;
        }
    }
}
