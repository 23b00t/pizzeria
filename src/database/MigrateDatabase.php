<?php

namespace app\database;

use PDOException;
use app\helpers\DatabaseHelper;

/**
 * Class MigrateDatabase
 *
 * This class is responsible for executing SQL commands from a specified SQL file
 * against a database using the DatabaseHelper class.
 */
class MigrateDatabase
{
    /**
     * Executes SQL commands from the specified file.
     *
     * @param  string $filePath   The path to the SQL file.
     * @param  string $dbUser     The database username.
     * @param  string $dbPassword The database password.
     * @return void
     */
    public function executeSqlFile($filePath, $dbUser, $dbPassword): void
    {
        // Create an instance of the DatabaseHelper
        $dbHelper = new DatabaseHelper($dbUser, $dbPassword);

        // Read the SQL file
        // https://www.php.net/manual/en/function.file-get-contents.php
        $sql = file_get_contents($filePath);
        if ($sql === false) {
            die("Error reading the file: $filePath\n");
        }

        // Split SQL commands
        // https://www.php.net/manual/en/function.explode.php
        $sqlCommands = explode(";", $sql);

        foreach ($sqlCommands as $command) {
            $command = trim($command);
            if (!empty($command)) {
                try {
                    // Execute the SQL command
                    $dbHelper->prepareAndExecute($command, []);
                    echo "Executed: $command\n";
                } catch (PDOException $e) {
                    echo "Error executing the command: $command\n";
                    echo "Error: " . $e->getMessage() . "\n";
                }
            }
        }
    }
}
