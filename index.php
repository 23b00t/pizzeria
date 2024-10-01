<?php

/*
* All POST and GET requests are handled here,
* which are then processed by the Router.
*/

require_once __DIR__ . '/Router.php';

// Routing-Logik
$requestUri = $_SERVER['REQUEST_URI'];

// Entferne Query-Parameter, falls vorhanden
$requestUri = explode('?', $requestUri)[1] ?? ''; 

if ($requestUri !== '') {
	$requestUri = rtrim($requestUri, '/'); // Trailing Slash entfernen
	$requestUri = parse_url($requestUri, PHP_URL_PATH); // Nur den Pfad verwenden
}

// Instantiate the Router class
$router = new Router();
$router->handleRequest($requestUri); // Auf Anfrage verarbeiten

exit();
