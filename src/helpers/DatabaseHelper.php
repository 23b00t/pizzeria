<?php

namespace app\helpers;

use PDO;
use PDOException;

/**
 * DatabaseHelper class responsible for establishing a connection to the database
 * and executing SQL queries using PDO.
 */
class DatabaseHelper
{
    /**
     * @var PDO|null $conn The static PDO connection instance for interacting with the database.
     */
    private static ?PDO $conn = null;

    /**
     * Static method to initialize the database connection if it hasn't been established yet.
     *
     * @param string $dbuser     The username for the database connection.
     * @param string $dbpassword The password for the database connection.
     *
     * @throws PDOException If the connection to the database fails.
     */
    public static function initializeConnection($dbuser, $dbpassword): void
    {
        if (self::$conn === null) {
            $servername = "127.0.0.1";
            $dbname = "pizzeria";
            $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";

            try {
                // Establish connection using PDO
                self::$conn = new PDO($dsn, $dbuser, $dbpassword);
                // Set PDO error mode to exception
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage() . "\n");
            }
        }
    }

    /**
     * Prepares and executes a SQL statement with the provided parameters.
     *
     * @param string $sql    The SQL statement to prepare and execute.
     * @param array  $params The parameters to bind to the SQL statement.
     *
     * @return array An associative array of fetched results.
     *
     * @throws PDOException If the execution of the statement fails.
     */
    public static function prepareAndExecute($sql, $params): array
    {
        // Ensure the connection is initialized
        if (self::$conn === null) {
            throw new PDOException("Database connection is not initialized.");
        }

        // Prepare the SQL statement
        $stmt = self::$conn->prepare($sql);

        // Execute the prepared statement
        $stmt->execute($params);

        // Fetch and return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Static method to close the database connection.
     *
     * @return void
     */
    public static function closeConnection(): void
    {
        self::$conn = null;
    }
}
