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
     * @var PDO|null $connRead
     */
    private static ?PDO $connRead = null;
    /**
     * @var PDO|null $connWrite
     */
    private static ?PDO $connWrite = null;

    /**
     *  Initialize DB Connection to read data
     * Static method to initialize the database connection if it hasn't been established yet.
     *
     * @throws PDOException If the connection to the database fails.
     */
    public static function initializeReadConnection(): void
    {
        if (self::$connRead === null) {
            $servername = "127.0.0.1";
            $dbname = "pizzeria";
            $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
            $dbuser = 'reader';
            $dbpassword = getenv('PW_READER');

            try {
                // Establish connection using PDO
                self::$connRead = new PDO($dsn, $dbuser, $dbpassword);
                // Set PDO error mode to exception
                self::$connRead->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage() . "\n");
            }
        }
    }

    /**
     * Initialize DB Connection to write data
     * Static method to initialize the database connection if it hasn't been established yet.
     *
     * @throws PDOException If the connection to the database fails.
     */
    public static function initializeWriteConnection(): void
    {
        if (self::$connWrite === null) {
            $servername = "127.0.0.1";
            $dbname = "pizzeria";
            $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
            $dbuser = 'writer';
            $dbpassword = getenv('PW_WRITER');

            try {
                // Establish connection using PDO
                self::$connWrite = new PDO($dsn, $dbuser, $dbpassword);
                // Set PDO error mode to exception
                self::$connWrite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
     * @param string $conn
     *
     * @return array An associative array of fetched results.
     *
     * @throws PDOException If the execution of the statement fails.
     */
    public static function prepareAndExecute(string $sql, array $params, string $conn): array
    {
        // Prepare the SQL statement
        $stmt = self::$$conn->prepare($sql);

        // Execute the prepared statement
        $stmt->execute($params);

        // Fetch and return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
