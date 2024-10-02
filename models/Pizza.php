<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

/**
 * Class Pizza
 *
 * Represents a pizza entity with properties and methods for database interactions.
 */
class Pizza extends BaseModel
{
    /** @var int|null The ID of the pizza. */
    private $id;

    /** @var string The name of the pizza. */
    private $name;

    /** @var float The price of the pizza. */
    private $price;

    /** @var array List of getter methods for Pizza properties. */
    protected static $getters = ['id', 'name', 'price'];

    /** @var array List of setter methods for Pizza properties. */
    protected static $setters = ['name', 'price'];

    /**
     * Pizza constructor.
     *
     * @param string $name The name of the pizza.
     * @param float $price The price of the pizza.
     * @param int|null $id The ID of the pizza (optional).
     */
    public function __construct($name, $price, $id = null)
    {
        $this->id = $id; 
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * Saves the pizza to the database.
     *
     * @return array The result of the save operation.
     */
    public function save(): array
    {
        // Define SQL query and parameters
        self::$sql = 'INSERT INTO pizza (name, price) VALUES (?, ?)';
        self::$params = [$this->name, $this->price];  

        return parent::save();
    }

    /**
     * Updates the pizza in the database.
     *
     * @return array The result of the update operation.
     */
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

    /**
     * Finds all ingredients for a specific pizza by its ID.
     *
     * @param int $pizzaId The ID of the pizza to find ingredients for.
     * @return array An array of ingredients and their quantities.
     * Example return: [['ingredient' => $ingredientObject, 'quantity' => $quantity]]
     */
    public static function findIngredientsByPizzaId($pizzaId): array
    {
        $db = new DatabaseHelper("reader", getenv('PW_READER'));

        $sql = "SELECT i.*, j.quantity 
                FROM ingredient i
                JOIN pizza_ingredient j ON i.id = j.ingredient_id
                WHERE j.pizza_id = ?";
        
        $params = [$pizzaId];
        $result = $db->prepareAndExecute($sql, $params);

        $ingredients = [];
        if ($result) {
            foreach ($result as $ingredientData) {
                $ingredients[] = [
                    'ingredient' => new Ingredient(
                        $ingredientData['name'],
                        $ingredientData['price'],
                        $ingredientData['vegetarian'],
                        $ingredientData['id']
                    ),
                    'quantity' => $ingredientData['quantity']
                ];
            }
        }

        return $ingredients;
    }

    /**
     * Finds all pizzas in the database.
     *
     * @return array An array of Pizza objects.
     */
    public static function findAll(): array
    {
        $result = parent::findAll();

        $pizzas = [];
        if ($result) {
            foreach ($result as $pizzaData) {
                $pizzas[] = new Pizza($pizzaData['name'], $pizzaData['price'], $pizzaData['id']);
            }
        }

        return $pizzas;
    }
}
