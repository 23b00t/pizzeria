<?php

namespace app\core;

use ReflectionMethod;

/**
 * Router class
 */
class Router
{
    private string $area;
    private string $action;
    private int $id;
    private array $formData;

    private string $view;

    private bool $redirect;

    private string $msg;


    /**
     * Constructor that starts a session if none is active.
     * @param string $area
     * @param string $action
     * @param int $id
     * @param array<int,mixed> $formData
     */
    public function __construct(
        string &$area,
        string &$action,
        string &$view,
        bool &$redirect,
        string &$msg,
        int $id,
        array $formData
    ) {
        // Check if a session is active; otherwise, start one
        session_status() === PHP_SESSION_NONE && session_start();

        $this->area = &$area;
        $this->action = &$action;
        $this->view = &$view;
        $this->redirect = &$redirect;
        $this->msg = &$msg;
        $this->id = $id;
        $this->formData = $formData;
    }

    /**
     * route
     *
     * @return array
     */
    public function route(): ?array
    {
        // get controller name including namespace from @param $area
        $controllerName = 'app\\controllers\\' . ucfirst($this->area) . 'Controller';

        // Check if a controller with this name exists
        // TODO: Implement in both if-blocks Error Handeling
        if (class_exists($controllerName)) {
            // Instanciate the controller
            $controller = new $controllerName($this->area, $this->action, $this->view, $this->redirect, $this->msg);

            // Check if the method, given in @param $action, exists in the controller
            if (method_exists($controller, $this->action)) {
                // Instanciate ReflectionMethod to read the expected paramters of the controller method
                $reflectionMethod = new ReflectionMethod($controller, $this->action);
                $parameters = $reflectionMethod->getParameters();
                $args = [];

                /** Iterate over the expected params an add them to array $args if given.
                 *  The controllers expect one of or both from int $id and array $formData.
                 *  TODO: This logic should be reviewed and could be more robust
                 */
                foreach ($parameters as $param) {
                    $paramName = $param->getName();

                    if ($paramName === 'id') {
                        $args[] = $this->id;
                    } elseif ($paramName === 'formData') {
                        $args[] = $this->formData;
                    }
                }

                // Call the method on the controller with $args
                return $reflectionMethod->invokeArgs($controller, $args);
            }
        }
    }
}
