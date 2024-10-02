<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

class Pizza extends BaseModel
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
        // Define SQL query and parameters
        self::$sql = 'INSERT INTO pizza (name, price) VALUES (?, ?)';
        self::$params = [$this->name, $this->price];  

        return parent::save();
    }

    public function update(): array
    {
        // Define SQL query and parameters for updating the pizza
        self::$sql = 'UPDATE pizza SET name = ?, price = ? WHERE id = ?';
        self::$params = [$this->name(), $this->price(), $this->id()];

        // Call the parent update method
        return parent::update();
    }

    /**
     * Finds a pizza by its ID.
     *
     * @param  int $id The ID of the pizza to find.
     * @return Pizza|null The Pizza object if found, null otherwise.
     */
    public static function findById($id): ?Pizza
    {
        $result = parent::findById($id);

        if ($result) {
            $pizzaData = $result[0];
            return new Pizza($pizzaData['name'], $pizzaData['price'], $pizzaData['id']);
        }

        return null;
    }

    public static function findAll(): array
    {
        $result = parent::findAll();

        $pizzas = [];
        if ($result) {
            foreach ($result as $pizzaData) {
                $pizzas[] = new Pizza($pizzaData['name'], $pizzaData['price'], $pizzaData['id']);
            }
        }

        return $pizzas; // Return an array of Pizza objects
    }
}
