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
$view = '';
$redirect = false;
$msg = '';

/**
 * @var array $return
 * Gets objects in associative arrays from Controllers via Router
 */
$return = (new Router($area, $action, $view, $redirect, $msg, $id, $data))->route();
is_array($return) && extract($return);

if ($redirect) {
    $return = (new Router($area, $action, $view, $redirect, $msg, $id, $data))->route();
    is_array($return) && extract($return);

    include __DIR__ . '/../src/views/application.html.php';
} else {
    include __DIR__ . '/../src/views/application.html.php';
}
