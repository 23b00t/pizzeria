<?php

require_once __DIR__ . '/Models/User.php';
require_once __DIR__ . '/Controllers/UserController.php';
require_once __DIR__ . '/Helpers/Helper.php';

/*
* Klasse zum handhaben von Requests
*/
class Router
{
    public function __construct()
    {
        // Überprüfe, ob session aktiv ist, ansonsten starte eine
        session_status() === PHP_SESSION_NONE && session_start();
    }

    // Überprüfe, um welchen Request es sich handelt und rufe die entsprechende 
    // Methode auf
    public function handleRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->handlePost();
        } elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
            $this->handleGet();
        } else {
            echo "Nur POST und GET";
        }
    }

    private function handlePost()
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

    private function handleGet()
    {
        if (isset($_GET['user_id'])) {
            // UserController instanziieren
            $userController = new UserController();

            // Benutzer-ID aus der URL abrufen
            $userId = $_GET['user_id'];

            // Die show-Methode des UserControllers aufrufen
            $userController->show($userId);
        } else {
            header('Location: ./Views/login_form.php');
        }
    }
}
