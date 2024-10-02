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
}
