<?php

require_once '/opt/lampp/htdocs/oop/Router.php';
// Instanziiere die Router-Klasse
$router = new Router();

$router->handleRequest();
exit();
