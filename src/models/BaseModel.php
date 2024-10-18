<?php

namespace app\models;

use app\core\BaseClass;
use app\helpers\DatabaseHelper;
use ReflectionClass;
use ReflectionProperty;

/**
 * Abstract Class BaseModel
 *
 * Defines generic methods for models
 *
 * @method int id() Inherited from BaseClass
 */
abstract class BaseModel extends BaseClass
{
    /**
     * Saves the current object to the database.
     * Dynamically generates an SQL INSERT statement based on the properties of the calling child class.
     *
     * @return array The result of the database operation.
     */
    public function save(): array
    {
        $tableName = static::getTableName();
        $columns = [];
        $placeholders = [];
        $values = [];

        // Reflects the calling child class to get its properties
        $reflection = new ReflectionClass(get_called_class());
        $props = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop) {
            $attribute = $prop->getName();

            // Normally, the 'id' field is auto-generated by the database, so we skip it
            if ($attribute !== 'id') {
                $columns[] = $attribute;
                $placeholders[] = '?';
                // Retrieves the value of the property from the instance
                $values[] = $prop->getValue($this);
            }
        }

        // Builds the SQL INSERT query
        $sql =
        'INSERT INTO ' . $tableName . ' (' . implode(',', $columns) . ') VALUES (' . implode(',', $placeholders) . ')';
        $db = new DatabaseHelper('writer', getenv('PW_WRITER'));

        // Executes the query and returns the result
        return $db->prepareAndExecute($sql, $values);
    }

    /**
     * Updates the current object in the database.
     * Dynamically generates an SQL UPDATE statement based on the properties of the calling child class.
     *
     * @return array The result of the database operation.
     */
    public function update(): array
    {
        $tableName = static::getTableName();
        $columns = [];
        $values = [];

        // Reflects the calling child class to get its properties
        $reflection = new ReflectionClass(get_called_class());
        $props = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop) {
            $attribute = $prop->getName();

            // Does not update the 'id' field
            if ($attribute !== 'id') {
                $columns[] = $attribute . ' = ?';
                // Retrieves the value of the property from the instance
                $values[] = $prop->getValue($this);
            }
        }

        // Retrieves the 'id' value for the WHERE clause
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idValue = $idProperty->getValue($this);
        $values[] = $idValue;

        // Builds the SQL UPDATE query
        $sql = 'UPDATE ' . $tableName . ' SET ' . implode(', ', $columns) . ' WHERE id = ?';
        $db = new DatabaseHelper('writer', getenv('PW_WRITER'));

        // Executes the query and returns the result
        return $db->prepareAndExecute($sql, $values);
    }

    /**
     * Deletes the current object from the database.
     * Generates an SQL DELETE query based on the object's ID.
     *
     * @return array The result of the delete operation.
     */
    public function delete(): array
    {
        // Establishes database connection
        $db = new DatabaseHelper('writer', getenv('PW_WRITER'));

        $tableName = static::getTableName();

        // Defines the SQL DELETE query
        $sql = 'DELETE FROM ' . $tableName . ' WHERE id = ?';
        $params = [$this->id()];

        // Executes the query and returns the result
        return $db->prepareAndExecute($sql, $params);
    }

    /**
     * Finds an object by a specified attribute.
     *
     * Use with unique values! Otherwise, it will only return the first result!
     *
     * @param  string $attribute The name of the attribute to search by.
     * @param  mixed  $value     The value of the attribute to search for.
     * @return static|null The object if found, null otherwise.
     */
    public static function findBy(string $value, string $attribute): ?self
    {
        // Establishes database connection
        $db = new DatabaseHelper('reader', getenv('PW_READER'));
        $tableName = static::getTableName();

        // Prepares the SQL query using the attribute name
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $attribute . ' = ?';
        $result = $db->prepareAndExecute($sql, [$value]);

        if ($result && count($result) > 0) {
            $data = $result[0];
            return self::mapDataToModel($data);
        }

        return null;
    }

    /**
     * Finds objects based on a custom WHERE statement.
     *
     * Example usage:
     *
     * $whereClause = 'user_id = ? AND date = TODAY() LIMIT 1';
     * $params = [5];  // Parameter for user_id
     * $models = Model::where($whereClause, $params);
     *
     * @param  string $whereClause The complete WHERE clause, including any conditions.
     * @param  array  $params      An array of parameters to bind to the SQL statement (optional).
     * @return array               An array of all model instances matching the condition.
     */
    public static function where(string $whereClause, array $params = []): array
    {
        // Establishes database connection
        $db = new DatabaseHelper('reader', getenv('PW_READER'));
        $tableName = static::getTableName();

        // Prepares the SQL query with the custom WHERE clause
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $whereClause;
        // Executes the query with the provided parameters
        $result = $db->prepareAndExecute($sql, $params);

        $models = [];
        foreach ($result as $data) {
            $models[] = self::mapDataToModel($data);
        }

        return $models;
    }

    /**
     * Finds all objects in the table.
     * Dynamically maps all database results to their respective model instances.
     *
     * @return array An array of all model instances.
     */
    public static function findAll(): array
    {
        $db = new DatabaseHelper('reader', getenv('PW_READER'));
        $tableName = static::getTableName();
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
     * Dynamically creates the model object using reflection,
     * matching the database data to the constructor parameters of the child class.
     *
     * @param  array $data The data from the database.
     * @return static The model instance with mapped data.
     */
    private static function mapDataToModel(array $data): self
    {
        // Instantiates the calling child class using the reflected parameters
        $modelClass = get_called_class();
        $reflection = new ReflectionClass($modelClass);

        // Retrieves the constructor parameters
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();

        // Prepares an array to store the values for the constructor
        $constructorArgs = [];

        foreach ($parameters as $param) {
            $paramName = $param->getName();

            // Matches data to the constructor parameters
            if (array_key_exists($paramName, $data)) {
                $constructorArgs[] = $data[$paramName];
            } else {
                // Uses default values for optional parameters if not present in the data
                $constructorArgs[] = $param->isOptional() ? $param->getDefaultValue() : null;
            }
        }

        // Creates an instance of the child class with the matched values
        return $reflection->newInstanceArgs($constructorArgs);
    }

    /**
     * Retrieves the table name based on the calling class name.
     * Converts the class name to lowercase to match the database table naming convention.
     *
     * @return string The table name.
     */
    protected static function getTableName(): string
    {
        // Get the class name including namespace
        $caller = get_called_class();

        // Use `basename` on the class name using namespace separator
        return strtolower(basename(str_replace('\\', '/', $caller)));
    }
}
