<?php

/**
 * public\index.php
 * All request come in here.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use app\core\Router;

$area = $_REQUEST['area'] ?? 'user';
$action = $_REQUEST['action'] ?? 'showLogin';
$id = $_REQUEST['id'] ?? 0;
$data = $_REQUEST;

/**
 * @var array $return
 * Gets data from Controllers via Router
 */
$return = (new Router($area, $action, $id, $data))->route();
extract($return);

/**
 * @var string $view (extracted from $return)
 * @var string $redirect (extracted from $return)
 *
 * Examples of $return:
 * [ 'redirect' => 'true', 'area' => 'pizza', 'action' => 'index']
 * ($redirect is set; instanciate new Router and call PizzaController#index)
 * [ 'view' => 'pizza/index', 'pizzas' => $pizzas]
 * ($redirect isn't set, include pizza/index.php and make $pizzas available)
 */
if (isset($redirect)) {
    /** @var array $return */
    $return = (new Router($area, $action, $id, $data))->route();
    extract($return);

    include __DIR__ . '/../src/views/' . $view . '.php';
} else {
    include __DIR__ . '/../src/views/' . $view . '.php';
}
