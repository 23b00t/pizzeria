<?php

namespace app\models;

use app\models\BaseModel;

/**
 * Class PizzaIngredient
 *
 * Represents a pizza-ingredient association with properties and methods for database interactions.
 *
 * @method int id()                  Retrieves the ID of the pizza ingredient association.
 * @method int pizza_id()           Retrieves the ID of the associated pizza.
 * @method int ingredient_id()      Retrieves the ID of the associated ingredient.
 * @method int quantity()            Retrieves the quantity of the ingredient in the pizza.
 * @method void quantity(int $quantity) Sets the quantity of the ingredient in the pizza.
 */
class PizzaIngredient extends BaseModel
{
    private int|null $id;
    private int $pizza_id;
    private int $ingredient_id;
    private int $quantity;

    /**
     * @var array List of getter methods for PizzaIngredient properties.
     */
    protected static $getters = ['id', 'pizza_id', 'ingredient_id', 'quantity'];

    /**
     * @var array List of setter methods for PizzaIngredient properties.
     */
    protected static $setters = ['quantity'];

    /**
     * PizzaIngredient constructor.
     *
     * @param int       $pizza_id       The ID of the pizza.
     * @param int       $ingredient_id  The ID of the ingredient.
     * @param int       $quantity       The quantity of the ingredient.
     * @param int|null  $id             The ID of the pizza ingredient association (optional).
     */
    public function __construct($pizza_id, $ingredient_id, $quantity, $id = null)
    {
        $this->id = $id; 
        $this->pizza_id = $pizza_id;
        $this->ingredient_id = $ingredient_id;
        $this->quantity = $quantity;
    }

    /**
     * Set db table name for use in parent class
     *
     * @return string The table name.
     */
    protected static function getTableName(): string
    {
        return 'pizza_ingredient';
    }
}
