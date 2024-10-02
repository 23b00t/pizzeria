<?php

require_once __DIR__ . '/../core/BaseClass.php';

abstract class BaseModel extends BaseClass
{
    /**
     * Saves the current object to the database.
     * It dynamically generates an SQL INSERT statement based on the properties of the calling child class.
     * @return array The result of the database operation.
     */
    public function save(): array
    {
        $tableName = self::getTableName();
        $columns = [];
        $placeholders = [];
        $values = [];

        // Reflect the calling child class to get its properties
        $reflection = new ReflectionClass(get_called_class());
        $props = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop) {
            $attribute = $prop->getName();

            // Normally, the 'id' field is auto-generated by the database, so we skip it
            if ($attribute !== 'id') {
                $columns[] = $attribute;
                $placeholders[] = '?';
                // Retrieve the value of the property from the instance
                $values[] = $prop->getValue($this);
            }
        }

        // Build the SQL INSERT query
        $sql = 'INSERT INTO ' . $tableName . ' (' . implode(',', $columns) . ') VALUES (' . implode(',', $placeholders) . ')';
        $db = new DatabaseHelper("writer", getenv('PW_WRITER'));

        // Execute the query and return the result
        return $db->prepareAndExecute($sql, $values);
    }

    /**
     * Updates the current object in the database.
     * It dynamically generates an SQL UPDATE statement based on the properties of the calling child class.
     * @return array The result of the database operation.
     */
    public function update(): array
    {
        $tableName = self::getTableName();
        $columns = [];
        $values = [];

        // Reflect the calling child class to get its properties
        $reflection = new ReflectionClass(get_called_class());
        $props = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop) {
            $attribute = $prop->getName();

            // Do not update the 'id' field
            if ($attribute !== 'id') {
                $columns[] = $attribute . ' = ?';
                // Retrieve the value of the property from the instance
                $values[] = $prop->getValue($this);
            }
        }

        // Retrieve the 'id' value for the WHERE clause
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idValue = $idProperty->getValue($this);
        $values[] = $idValue;

        // Build the SQL UPDATE query
        $sql = 'UPDATE ' . $tableName . ' SET ' . implode(', ', $columns) . ' WHERE id = ?';
        $db = new DatabaseHelper("writer", getenv('PW_WRITER'));

        // Execute the query and return the result
        return $db->prepareAndExecute($sql, $values);
    }

    /**
     * Deletes the current object from the database.
     * This method generates an SQL DELETE query based on the object's ID.
     * @return array The result of the delete operation.
     */
    public function delete(): array
    {
        // Establish database connection
        $db = new DatabaseHelper("writer", getenv('PW_WRITER'));

        $tableName = self::getTableName();

        // Define the SQL DELETE query
        $sql = 'DELETE FROM ' . $tableName . ' WHERE id = ?';
        $params = [$this->id()];

        // Execute the query and return the result
        return $db->prepareAndExecute($sql, $params);
    }

    /**
     * Finds an object by its ID.
     * This method dynamically maps the database result to the appropriate model instance.
     * @param int $id The ID of the object to find.
     * @return static|null The object instance if found, null otherwise.
     */
    public static function findById($id): ?self
    {
        $db = new DatabaseHelper("reader", getenv('PW_READER'));
        $tableName = self::getTableName();
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE id = ?';
        $result = $db->prepareAndExecute($sql, [$id]);

        if ($result && count($result) > 0) {
            $data = $result[0];
            return self::mapDataToModel($data);
        }

        return null;
    }

    /**
     * Finds all objects in the table.
     * This method dynamically maps all database results to their respective model instances.
     * @return array An array of all model instances.
     */
    public static function findAll(): array
    {
        $db = new DatabaseHelper("reader", getenv('PW_READER'));
        $tableName = self::getTableName();
        $sql = 'SELECT * FROM ' . $tableName;
        $result = $db->prepareAndExecute($sql, []);

        $models = [];
        foreach ($result as $data) {
            $models[] = self::mapDataToModel($data);
        }

        return $models;
    }

    /**
     * Maps database result data to the appropriate model instance.
     * This method dynamically creates the model object using reflection, 
     * matching the database data to the constructor parameters of the child class.
     * @param array $data The data from the database.
     * @return static The model instance with mapped data.
     */
    private static function mapDataToModel(array $data): self
    {
        // Instantiate the calling child class using the reflected parameters
        $modelClass = get_called_class();
        $reflection = new ReflectionClass($modelClass);

        // Retrieve the constructor parameters
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();

        // Prepare an array to store the values for the constructor
        $constructorArgs = [];

        foreach ($parameters as $param) {
            $paramName = $param->getName();

            // Match data to the constructor parameters
            if (array_key_exists($paramName, $data)) {
                $constructorArgs[] = $data[$paramName];
            } else {
                // Use default values for optional parameters if not present in the data
                $constructorArgs[] = $param->isOptional() ? $param->getDefaultValue() : null;
            }
        }

        // Create an instance of the child class with the matched values
        return $reflection->newInstanceArgs($constructorArgs);
    }

    /**
     * Retrieves the table name based on the calling class name.
     * Converts the class name to lowercase to match the database table naming convention.
     * @return string The table name.
     */
    private static function getTableName()
    {
        // Get the name of the calling class and convert it to lowercase
        $calledClass = get_called_class();

        return strtolower($calledClass);
    }
}
