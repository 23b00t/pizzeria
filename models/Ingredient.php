<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

class Ingredient extends BaseModel
{
    private $id;
    private $name;
    private $price;
    private $vegetarian;

    protected static $getters = ['id', 'name', 'price', 'vegetarian'];
    protected static $setters = ['name', 'price', 'vegetarian'];

    public function __construct($name, $price, $vegetarian, $id = null)
    {
        $this->id = $id; 
        $this->name = $name;
        $this->price = $price;
        $this->vegetarian = $vegetarian ?? 0;
    }

    public function save(): array
    {
        // Define SQL query and parameters
        self::$sql = 'INSERT INTO ingredient (name, price, vegetarian) VALUES (?, ?, ?)';
        self::$params = [$this->name, $this->price, $this->vegetarian];  

        return parent::save();
    }

    public function update(): array
    {
        // Define SQL query and parameters for updating the ingredient
        self::$sql = 'UPDATE ingredient SET name = ?, price = ?, vegetarian = ? WHERE id = ?';
        self::$params = [$this->name(), $this->price(), $this->vegetarian(), $this->id()];

        // Call the parent update method
        return parent::update();
    }

    /**
     * Finds a ingredient by its ID.
     *
     * @param  int $id The ID of the ingredient to find.
     * @return Ingredient|null The Ingredient object if found, null otherwise.
     */
    public static function findById($id): ?Ingredient
    {
        $result = parent::findById($id);

        if ($result) {
            $ingredientData = $result[0];
            return new Ingredient($ingredientData['name'], $ingredientData['price'], $ingredientData['vegetarian'], $ingredientData['id']);
        }

        return null;
    }

    public static function findAll(): array
    {
        $result = parent::findAll();

        $ingredients = [];
        if ($result) {
            foreach ($result as $ingredientData) {
                $ingredients[] = new Ingredient($ingredientData['name'], $ingredientData['price'], $ingredientData['vegetarian'], $ingredientData['id']);
            }
        }

        return $ingredients; // Return an array of Ingredient objects
    }
}
