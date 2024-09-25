<?php

require_once __DIR__ . '/Router.php';
/*
* Hier her finden all POST und GET requests statt, die dann
* vom Router verarbeitet werden. 
*/
// Instanziiere die Router-Klasse
$router = new Router();
$router->handleRequest();

exit();
