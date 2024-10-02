<?php

// INFO: Return types
// https://dev.to/karleb/return-types-in-php-3fip

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/PizzaController.php';
require_once __DIR__ . '/../helpers/Helper.php';

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
    private $route;
    /**
     * Constructor that starts a session if none is active.
     */
    public function __construct()
    {
        // Check if a session is active, otherwise start one
        session_status() === PHP_SESSION_NONE && session_start();

        $requestUri = $_SERVER['REQUEST_URI'];

        // Extract query param if present, else '' (?? null coalescing operator)
        $route = explode('?', $requestUri)[1] ?? ''; 
        $route = rtrim($route, '/'); 
        $this->route = $route;
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
            // To avoid missmatches of the preg_match statement
            $this->route === '' && header('Location: ./views/User/login_form.php') && exit();

            $this->handleGet();
        }
    }

    /**
     * Handles POST requests for login, registration, and signout.
     * Validates the CSRF token before processing.
     * 
     * @return void
     */
    private function handlePost(): mixed
    {
        // Logout user
        isset($_POST["signout"]) && UserController::signOut();

        // Check if valid CSRF Token is present
        Helper::checkCSRFToken();

        // User routes
        if (isset($_POST['login'])) { return (new UserController())->login($_POST); }
        if (isset($_POST['register'])) { return (new UserController())->create($_POST); }

        // Pizza routes
        if ($this->route === 'Pizza/store') { 
            return (new PizzaController())->store($_POST); 
        }
        if (preg_match('/Pizza\/update\/(\d+)$/', $this->route, $matches)) { 
            return (new PizzaController())->update($matches[1], $_POST); 
        }

        // If no condition is met exit(); Destroys pointers on stack and GC cleans up heap
        exit();
    }

    /**
     * Handles GET requests, specifically for displaying user information.
     * Redirects to the login form if no user_id is provided.
     * 
     * @return void
     */

    private function handleGet(): mixed 
    {
        switch (true) {
            case $this->route === 'Pizza/index': 
                return (new PizzaController())->index();

            case preg_match('/Pizza\/show\/(\d+)$/', $this->route, $matches):
                return (new PizzaController())->show($matches[1]);

            case preg_match('/Pizza\/edit\/(\d+)$/', $this->route, $matches):
                return (new PizzaController())->edit($matches[1]);

            case $this->route === 'Pizza/create':
                return (new PizzaController())->create();

            case preg_match('/Pizza\/delete\/(\d+)$/', $this->route, $matches):
                return (new PizzaController())->delete($matches[1]);

            case preg_match('/user_id=(\d+)$/', $this->route, $matches):
                return (new UserController())->show($matches[1]);
        }

        exit();
    }
}
