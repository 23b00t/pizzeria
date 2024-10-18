<?php

namespace app\core;

use ReflectionMethod;

/**
 * Router class for processing and handling HTTP requests.
 *
 * This class checks the request type (GET or POST) and calls the corresponding
 * method to handle the request. It automatically starts a session if none is active.
 */
class Router
{
    private string $area;
    private string $action;
    private int $id;
    private array $formData;


    /**
     * Constructor that starts a session if none is active.
     * @param string $area
     * @param string $action
     * @param array<int,mixed> $formData
     */
    public function __construct(string $area, string $action, int $id, array $formData)
    {
        // Check if a session is active; otherwise, start one
        session_status() === PHP_SESSION_NONE && session_start();

        $this->area = $area;
        $this->action = $action;
        $this->id = $id;
        $this->formData = $formData;
    }

    /**
     * route
     *
     * @return string
     */
    public function route(): array
    {
        $controllerName = 'app\\controllers\\' . ucfirst($this->area) . 'Controller';

        if (class_exists($controllerName)) {
            $controller = new $controllerName();

            if (method_exists($controller, $this->action)) {
                $reflectionMethod = new ReflectionMethod($controller, $this->action);
                $parameters = $reflectionMethod->getParameters();
                $args = [];

                foreach ($parameters as $param) {
                    $paramName = $param->getName();  // Name des Parameters

                    // Wert aus dem formData-Array entnehmen, wenn vorhanden
                    if ($paramName === 'id') {
                        $args[] = $this->id;
                    } elseif ($paramName === 'formData') {
                        $args[] = $this->formData;
                    }
                }

                // Die Methode mit den gefundenen Argumenten aufrufen
                return $reflectionMethod->invokeArgs($controller, $args);
            }
        }
    }
}
