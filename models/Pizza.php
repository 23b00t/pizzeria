<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

/**
 * Class Pizza
 *
 * Represents a pizza entity with properties and methods for database interactions.
 *
 * @method int id()
 * @method string name()
 * @method string price()
 */
class Pizza extends BaseModel
{
    private $id;
    private $name;
    private $price;

    /**
     * @var array List of getter methods for Pizza properties. 
     */
    protected static $getters = ['id', 'name', 'price'];

    /**
     * @var array List of setter methods for Pizza properties. 
     */
    protected static $setters = ['name', 'price'];

    /**
     * Pizza constructor.
     *
     * @param string   $name  The name of the pizza.
     * @param float    $price The price of the pizza.
     * @param int|null $id    The ID of the pizza (optional).
     */
    public function __construct($name, $price, $id = null)
    {
        $this->id = $id; 
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * Finds all ingredients for a specific pizza by its ID.
     *
     * @param  int $pizzaId The ID of the pizza to find ingredients for.
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
}
