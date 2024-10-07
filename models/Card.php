<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

/**
 * Class Card
 *
 * Represents a card entity with properties and methods for database interactions.
 *
 * @method int id()
 */
class Card extends BaseModel
{
    private int|null $id;
    private int      $pizza_id;
    private int      $purchase_id;
    private int      $quantity;

    /**
     * @var array List of getter methods for Card properties.
     */
    protected static $getters = ['id', 'pizza_id', 'purchase_id', 'quantity'];

    /**
     * @var array List of setter methods for Card properties.
     */
    protected static $setters = ['quantity'];

    /**
     * Card constructor.
     *
     * @param int|null $id    The ID of the card (optional).
     */
    public function __construct($pizza_id, $purchase_id, $quantity, $id = null)
    {
        $this->id = $id;
        $this->pizza_id = $pizza_id;
        $this->purchase_id = $purchase_id;
        $this->quantity = $quantity;
    }
}
