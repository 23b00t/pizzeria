<?php

/**
 * public\index.php
 * All request come in here.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use app\core\Router;

$area = $_REQUEST['area'] ?? 'user';
$action = $_REQUEST['action'] ?? 'index';
$id = $_REQUEST['id'] ?? 0;
$view = '';
$msg = '';

/**
 * Determine the request method (POST or GET) and securely pass the corresponding
 * data ($_POST or $_GET) to the controller, ensuring proper handling of input.
 */
$data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

/**
 * @var array $return
 * Gets objects in associative arrays from Controllers via Router
 */
$response = (new Router($area, $action, $id, $data))->route();
/**
 * If the value is not set by the controller, it defaults to the pre-set value.
 * In this case, the value should not be changed.
 */
$view = empty($response->getView()) ? $view : $response->getView();
$action = empty($response->getAction()) ? $action : $response->getAction();
$area = empty($response->getArea()) ? $area : $response->getArea();
$msg = $response->getMsg();

$objectArray = $response->getObjects();

/** Extract the named object array */
extract($objectArray);

include __DIR__ . '/../src/views/application.html.php';
