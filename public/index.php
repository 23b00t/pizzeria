<?php

/**
 * public\index.php
 * All request come in here.
 */

use app\core\Router;

require_once __DIR__ . '/../vendor/autoload.php';

try {
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
    $view = $response->getView();
    $msg = $response->getMsg();

    $objectArray = $response->getObjects();

    /** Extract the named object array */
    extract($objectArray);
} catch (\Throwable $th) {
    /** Catch all other unpredictable errors and exceptions */
    $timestamp = (new DateTime())->format('Y-m-d H:i:s ');
    file_put_contents('/opt/lampp/logs/pizzeria.log', $timestamp . $th->getMessage() . "\n", FILE_APPEND);
    $view = 'error';
} finally {
    include __DIR__ . '/../src/views/application.html.php';
}
