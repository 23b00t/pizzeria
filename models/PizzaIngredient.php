<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

/**
 * Class PizzaIngredient
 *
 * @method int id()
 * @method int pizza_id()
 * @method int ingredient_id()
 * @method int quantity();
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
    protected static $setters = [];

    /**
     * PizzaIngredient constructor.
     *
     * @param int       $pizza_id       The id of the pizza
     * @param int       $ingredient_id  The id of the ingredient
     * @param int       $quantity       The quantity of the ingredient
     * @param int|null  $id             The ID of the ingredient (optional).
     */
    public function __construct($pizza_id, $ingredient_id, $quantity, $id = null)
    {
        $this->id = $id; 
        $this->pizza_id = $pizza_id;
        $this->ingredient_id = $ingredient_id;
        $this->quantity = $quantity;
    }
}
