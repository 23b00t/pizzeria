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
     * @var ?PDO $connRead
     */
    private static ?PDO $connRead = null;
    /**
     * @var ?PDO $connWrite
     */
    private static ?PDO $connWrite = null;

    /**
     * Static method to initialize the database connection based on type.
     *
     * @param string $type 'read' or 'write'
     * @throws PDOException If the connection to the database fails.
     */
    private static function initializeConnection(string $type): void
    {
        $servername = "127.0.0.1";
        $dbname = "pizzeria";
        $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
        $dbuser = $type === 'read' ? 'reader' : 'writer';
        $dbpassword = getenv($type === 'read' ? 'PW_READER' : 'PW_WRITER');

        try {
            if ($type === 'read' && self::$connRead === null) {
                self::$connRead = new PDO($dsn, $dbuser, $dbpassword);
                self::$connRead->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } elseif ($type === 'write' && self::$connWrite === null) {
                self::$connWrite = new PDO($dsn, $dbuser, $dbpassword);
                self::$connWrite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {
            throw new PDOException("Connection failed: " . $e->getMessage());
        }
    }

    public static function initializeReadConnection(): void
    {
        self::initializeConnection('read');
    }

    public static function initializeWriteConnection(): void
    {
        self::initializeConnection('write');
    }

    /**
     * Prepares and executes a SQL statement with the provided parameters.
     *
     * @param string $sql    The SQL statement to prepare and execute.
     * @param array  $params The parameters to bind to the SQL statement.
     * @param string $conn   'connRead' or 'connWrite'
     *
     * @return array An associative array of fetched results.
     *
     * @throws PDOException If the execution of the statement fails.
     */
    public static function prepareAndExecute(string $sql, array $params, string $conn): array
    {
        $connection = self::$$conn;
        if (!$connection) {
            throw new PDOException("Connection not initialized.");
        }

        $stmt = $connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
