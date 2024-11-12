<?php

namespace app\controllers;

abstract class BaseController
{
    /**
     * handleDatabaseOperation
     *
     * Extract the database operation and error handling into a separate method
     *
     * @param callable $operation
     * @return Response
     */
    private function handleDatabaseOperation(callable $operation): Response
    {
        try {
            return $operation();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $response = $this->index();
            $response->setMsg('error=Fehler');
            return $response;
        }
    }
}
