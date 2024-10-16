<?php

namespace app\helpers;

use PDO;
use PDOException;

// INFO: Resourcen:
// https://www.w3schools.com/php/php_mysql_connect.asp
// https://www.php.net/manual/en/ref.pdo-mysql.php
// https://www.ibm.com/docs/en/dscp/10.1.0?topic=ess-preparing-executing-sql-statements
// https://www.ibm.com/docs/en/dscp/10.1.0?topic=rqrs-fetching-rows-columns-from-result-sets
// https://www.php.net/manual/de/pdo.constants.php#pdo.constants.fetch-assoc

/**
 * DatabaseHelper class responsible for establishing a connection to the database 
 * and executing SQL queries using PDO.
 * 
 * This class handles the database connection and provides methods to prepare 
 * and execute SQL statements. It ensures that the connection is properly closed 
 * when the instance is destroyed.
 * 
 * Properties:
 * 
 * - $_conn: PDO connection instance for interacting with the database.
 */
class DatabaseHelper
{
    /**
     * @var PDO $_conn The PDO connection instance for interacting with the database.
     */
    private $_conn;

    /**
     * Constructor to initialize the DatabaseHelper and establish a database connection.
     * 
     * @param string $dbuser     The username for the database connection.
     * @param string $dbpassword The password for the database connection.
     * 
     * @throws PDOException If the connection to the database fails.
     */
    public function __construct($dbuser, $dbpassword)
    {
        $servername = "127.0.0.1";
        $dbname = "pizzeria";
        $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";

        try {
            // Establish connection using PDO
            $this->_conn = new PDO($dsn, $dbuser, $dbpassword);
            // Set PDO error mode to exception
            $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage() . "\n");
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
    public function prepareAndExecute($sql, $params): array
    {
        // Prepare the SQL statement
        $stmt = $this->_conn->prepare($sql);

        // Execute the prepared statement
        $stmt->execute($params);

        // Fetch and return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Destructor to close the database connection.
     * 
     * This method is called when the DatabaseHelper instance is destroyed, 
     * ensuring that the connection to the database is properly closed.
     *
     * @return void
     */
    public function __destruct()
    {
        // Close the database connection
        $this->_conn = null;
    }
}
