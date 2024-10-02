<?php

// INFO: Return types
// https://dev.to/karleb/return-types-in-php-3fip

require_once __DIR__ . '/../helpers/Helper.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/PizzaController.php';
require_once __DIR__ . '/../controllers/IngredientController.php';

/**
 * Router class for processing and handling HTTP requests.
 * 
 * This class checks the request type (GET or POST) and calls the corresponding 
 * method to handle the request. It automatically starts a session if none is active.
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // To avoid missmatches of the preg_match statement
            $this->route === '' && header('Location: ./views/user/login_form.php') && exit();

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
        // Logout user
        isset($_POST['signout']) && UserController::signOut();

        // Check if valid CSRF Token is present
        Helper::checkCSRFToken();

        // Handle user routes
        if (isset($_POST['login'])) {
            (new UserController())->login($_POST);
        } elseif (isset($_POST['register'])) {
            (new UserController())->create($_POST);
        } 

        // Handle pizza routes
        elseif ($this->route === 'pizza/store') { 
            (new PizzaController())->store($_POST);
        } elseif (preg_match('/pizza\/update\/(\d+)$/', $this->route, $matches)) { 
            (new PizzaController())->update($matches[1], $_POST);
        }

        // Handle ingredient routes
        elseif ($this->route === 'ingredient/store') { 
            (new IngredientController())->store($_POST);
        } elseif (preg_match('/ingredient\/update\/(\d+)$/', $this->route, $matches)) { 
            (new IngredientController())->update($matches[1], $_POST);
        } 
        // exit() to clean up
        exit();
    }

    /**
     * Handles GET requests, specifically for displaying user information.
     * Redirects to the login form if no user_id is provided.
     *
     * @return void 
     */
    private function handleGet(): void
    {
        // Pizza routes
        if ($this->route === 'pizza/index') { 
            (new PizzaController())->index();
        } elseif (preg_match('/pizza\/show\/(\d+)$/', $this->route, $matches)) {
            (new PizzaController())->show($matches[1]);
        } elseif (preg_match('/pizza\/edit\/(\d+)$/', $this->route, $matches)) {
            (new PizzaController())->edit($matches[1]);
        } elseif ($this->route === 'pizza/create') {
            (new PizzaController())->create();
        } elseif (preg_match('/pizza\/delete\/(\d+)$/', $this->route, $matches)) {
            (new PizzaController())->delete($matches[1]);
        } 

        // Ingredient routes
        elseif ($this->route === 'ingredient/index') { 
            (new IngredientController())->index();
        } elseif (preg_match('/ingredient\/edit\/(\d+)$/', $this->route, $matches)) {
            (new IngredientController())->edit($matches[1]);
        } elseif ($this->route === 'ingredient/create') {
            (new IngredientController())->create();
        } elseif (preg_match('/ingredient\/delete\/(\d+)$/', $this->route, $matches)) {
            (new IngredientController())->delete($matches[1]);
        } 

        // User routes
        elseif (preg_match('/user_id=(\d+)$/', $this->route, $matches)) {
            (new UserController())->show($matches[1]);
        } 
        exit();
    }
}
