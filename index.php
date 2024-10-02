<?php

/*
* All POST and GET requests are handled here,
* which are then processed by the Router.
*/

require_once __DIR__ . '/core/Router.php';

// Instantiate the Router class
$router = new Router();
$router->handleRequest(); 

exit();
