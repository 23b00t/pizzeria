<?php

namespace app\controllers;

use Exception;
use PDOException;
use app\core\Response;
use app\models\User;

abstract class BaseController
{
    /**
     * handleDatabaseOperation
     *
     * Centralized method for database operations with error handling and dynamic controller
     *
     * @param callable $operation
     * @param mixed $controller
     * @return Response
     */
    protected function handleDatabaseOperation(callable $operation, $controller): Response
    {
        try {
            return $operation();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $response = $controller->index();
            $response->setMsg('error=Upps... Es ist ein Fehler aufgetreten!');
            return $response;
        }
    }

    /**
     * @return void
     */
    protected function authorize(): void
    {
        if (!User::isAdmin()) {
            throw new Exception('Aktion nicht erlaubt!');
        }
    }

    /**
     * index
     *
     * @return Response
     */
    abstract public function index(): Response;
}
