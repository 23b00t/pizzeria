<?php

require_once __DIR__ . '/../core/BaseClass.php';

abstract class BaseModel extends BaseClass
{
    protected static $sql;
    protected static $params;

    public function save(): array
    {
        // Establish database connection
        $db = new DatabaseHelper("writer", getenv('PW_WRITER'));

        // Insert into the database
        return $db->prepareAndExecute(static::$sql, static::$params);  
    }

    public function update(): array
    {
        // Establish database connection
        $db = new DatabaseHelper("writer", getenv('PW_WRITER'));

        // Execute the query with the provided SQL and parameters
        return $db->prepareAndExecute(static::$sql, static::$params);  
    }

    public function delete(): array
    {
        // Establish database connection
        $db = new DatabaseHelper("writer", getenv('PW_WRITER'));

        $tableName = self::getTableName();

        // Define SQL query and parameters for updating
        $sql = 'DELETE FROM ' . $tableName . ' WHERE id = ?';
        $params = [$this->id()];  

        // Update the database
        return $db->prepareAndExecute($sql, $params);  
    }

    /**
     * Finds a object by its ID.
     *
     */
    public static function findById($id): mixed 
    {
        // Establish database connection
        $db = new DatabaseHelper("reader", getenv('PW_READER'));
        
        $tableName = self::getTableName();

        // Define SQL query and parameters
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE id = ?';
        $params = [$id];

        // Execute the query
        return $db->prepareAndExecute($sql, $params);
    }

    public static function findAll(): mixed
    {
        // Establish database connection
        $db = new DatabaseHelper("reader", getenv('PW_READER'));
        
        $tableName = self::getTableName();

        // Define SQL query and parameters
        $sql = 'SELECT * FROM ' . $tableName;
        $params = [];

        // Execute the query
        return $db->prepareAndExecute($sql, $params);
    }

    private static function getTableName()
    {
        // Get the name of the calling class and convert it to lowercase
        $calledClass = get_called_class();

        return strtolower($calledClass);
    }
}
