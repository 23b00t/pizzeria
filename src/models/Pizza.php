<?php

namespace app\models;

use app\models\BaseModel;
use app\helpers\DatabaseHelper;

/**
 * Class Pizza
 *
 * Represents a pizza entity with properties and methods for database interactions.
 *
 * @method int id()                Retrieves the ID of the pizza.
 * @method string name()           Retrieves the name of the pizza.
 * @method float price()           Retrieves the price of the pizza.
 * @method void name(string $name) Sets the name of the pizza.
 * @method void price(float $price) Sets the price of the pizza.
 */
class Pizza extends BaseModel
{
    private int|null $id;
    private string $name;
    private float $price;

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
    public static function findIngredientsByPizzaId(string $pizzaId): array
    {
        DatabaseHelper::initializeConnection("reader", getenv('PW_READER'));

        $sql = "SELECT i.*, j.quantity 
                FROM ingredient i
                JOIN pizza_ingredient j ON i.id = j.ingredient_id
                WHERE j.pizza_id = ?";

        $params = [$pizzaId];
        $result = DatabaseHelper::prepareAndExecute($sql, $params);

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
