<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

/**
 * Class Ingredient
 *
 * Represents an ingredient entity with properties and methods for database interactions.
 */
class Ingredient extends BaseModel
{
    /** @var int|null The ID of the ingredient. */
    private $id;

    /** @var string The name of the ingredient. */
    private $name;

    /** @var float The price of the ingredient. */
    private $price;

    /** @var bool Indicates whether the ingredient is vegetarian (1 for true, 0 for false). */
    private $vegetarian;

    /** @var array List of getter methods for Ingredient properties. */
    protected static $getters = ['id', 'name', 'price', 'vegetarian'];

    /** @var array List of setter methods for Ingredient properties. */
    protected static $setters = ['name', 'price', 'vegetarian'];

    /**
     * Ingredient constructor.
     *
     * @param string $name The name of the ingredient.
     * @param float $price The price of the ingredient.
     * @param bool|null $vegetarian Indicates whether the ingredient is vegetarian.
     * @param int|null $id The ID of the ingredient (optional).
     */
    public function __construct($name, $price, $vegetarian, $id = null)
    {
        $this->id = $id; 
        $this->name = $name;
        $this->price = $price;
        $this->vegetarian = $vegetarian ?? 0;
    }
}
