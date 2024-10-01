<?php

require_once __DIR__ . '/../Helpers/DatabaseHelper.php';
require_once __DIR__ . '/../BaseClass.php';

class Pizza extends BaseClass
{
    private $id;
    private $name;
    private $price;

    protected static $getters = ['id', 'name', 'price'];
    protected static $setters = ['name', 'price'];

    public function __construct($name, $price, $id = null)
    {
        $this->id = $id; 
        $this->name = $name;
        $this->price = $price;
    }

    public function save(): array
    {
        // Establish database connection
        $db = new DatabaseHelper("writer", getenv('PW_WRITER'));

        // Define SQL query and parameters
        $sql = 'INSERT INTO pizza (name, price) VALUES (?, ?)';
        $params = [$this->name, $this->price];  

        // Insert the pizza into the database
        return $db->prepareAndExecute($sql, $params);  
    }

    public function update(): array
    {
        // Establish database connection
        $db = new DatabaseHelper("writer", getenv('PW_WRITER'));

        // Define SQL query and parameters for updating the pizza
        $sql = 'UPDATE pizza SET name = ?, price = ? WHERE id = ?';
        $params = [$this->name(), $this->price(), $this->id()];  

        // Update the pizza in the database
        return $db->prepareAndExecute($sql, $params);  
    }

    public function delete(): array
    {
        // Establish database connection
        $db = new DatabaseHelper("writer", getenv('PW_WRITER'));

        // Define SQL query and parameters for updating the pizza
        $sql = 'DELETE FROM pizza WHERE id = ?';
        $params = [$this->id()];  

        // Update the pizza in the database
        return $db->prepareAndExecute($sql, $params);  
    }

    /**
     * Finds a pizza by its ID.
     *
     * @param  int $id The ID of the pizza to find.
     * @return Pizza|null The Pizza object if found, null otherwise.
     */
    public static function findById($id): ?Pizza
    {
        // Establish database connection
        $db = new DatabaseHelper("reader", getenv('PW_READER'));
        
        // Define SQL query and parameters
        $sql = 'SELECT * FROM pizza WHERE id = ?';
        $params = [$id];

        // Execute the query
        $result = $db->prepareAndExecute($sql, $params);

        if ($result) {
            $pizzaData = $result[0];
            return new Pizza($pizzaData['name'], $pizzaData['price'], $pizzaData['id']);
        }

        return null;
    }

    public static function findAll()
    {
        // Establish database connection
        $db = new DatabaseHelper("reader", getenv('PW_READER'));
        
        // Define SQL query and parameters
        $sql = 'SELECT * FROM pizza';
        $params = [];

        // Execute the query
        $result = $db->prepareAndExecute($sql, $params);

        $pizzas = [];
        if ($result) {
            foreach ($result as $pizzaData) {
                $pizzas[] = new Pizza($pizzaData['name'], $pizzaData['price'], $pizzaData['id']);
            }
        }

        return $pizzas; // Return an array of Pizza objects
    }
}
