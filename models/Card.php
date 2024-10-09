<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

/**
 * Class Card
 *
 * Represents a card entity with properties and methods for database interactions.
 *
 * @method int|null id() Returns the ID of the card.
 * @method int|null pizza_id() Returns the ID of the associated pizza.
 * @method int|null purchase_id() Returns the ID of the associated purchase.
 * @method int quantity() Returns the quantity of the card.
 * @method void quantity(int $quantity) Sets the quantity of the card.
 */
class Card extends BaseModel
{
    private int|null $id;
    private int|null $pizza_id;
    private int|null $purchase_id;
    private int $quantity;

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
     * @param int|null $pizza_id     The ID of the associated pizza.
     * @param int|null $purchase_id   The ID of the associated purchase.
     * @param int      $quantity      The quantity of the card.
     * @param int|null $id           The ID of the card (optional).
     */
    public function __construct($pizza_id, $purchase_id, $quantity, $id = null)
    {
        $this->id = $id;
        $this->pizza_id = $pizza_id;
        $this->purchase_id = $purchase_id;
        $this->quantity = $quantity;
    }
}
