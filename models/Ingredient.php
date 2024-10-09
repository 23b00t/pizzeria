<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

/**
 * Class Ingredient
 *
 * Represents an ingredient entity with properties and methods for database interactions.
 *
 * @method int id()           Retrieves the ID of the ingredient.
 * @method string name()      Retrieves the name of the ingredient.
 * @method float price()      Retrieves the price of the ingredient.
 * @method int|null vegetarian() Retrieves the vegetarian status of the ingredient (1 if vegetarian, 0 otherwise).
 * @method void name(string $name) Sets the name of the ingredient.
 * @method void price(float $price) Sets the price of the ingredient.
 * @method void vegetarian(int $vegetarian) Sets the vegetarian status of the ingredient (1 for yes, 0 for no).
 */
class Ingredient extends BaseModel
{
    private int|null $id;
    private string $name;
    private float $price;
    private int|null $vegetarian;

    /**
     * @var array List of getter methods for Ingredient properties.
     */
    protected static $getters = ['id', 'name', 'price', 'vegetarian'];

    /**
     * @var array List of setter methods for Ingredient properties.
     */
    protected static $setters = ['name', 'price', 'vegetarian'];

    /**
     * Ingredient constructor.
     *
     * @param string    $name       The name of the ingredient.
     * @param float     $price      The price of the ingredient.
     * @param int|null  $vegetarian  Indicates whether the ingredient is vegetarian (1 for yes, 0 for no).
     * @param int|null  $id         The ID of the ingredient (optional).
     */
    public function __construct($name, $price, $vegetarian, $id = null)
    {
        $this->id = $id; 
        $this->name = $name;
        $this->price = $price;
        $this->vegetarian = $vegetarian;
    }
}
