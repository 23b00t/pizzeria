<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

/**
 * Class Purchase
 *
 * Represents a purchase entity with properties and methods for database interactions.
 *
 * @method int id()                         Retrieves the ID of the purchase.
 * @method int user_id()                    Retrieves the ID of the user who made the purchase.
 * @method string status()                  Retrieves the status of the purchase.
 * @method string purchased_at()            Retrieves the timestamp of when the purchase was made.
 * @method string|null delivered_at()       Retrieves the timestamp of when the purchase was delivered (if applicable).
 * @method void status(string $status)      Sets the status of the purchase.
 */
class Purchase extends BaseModel
{
    private $id;
    private $user_id;
    private $purchased_at;
    private $delivered_at;
    private $status;

    /**
     * @var array List of getter methods for Purchase properties. 
     */
    protected static $getters = ['id', 'user_id', 'status', 'purchased_at', 'delivered_at'];

    /**
     * @var array List of setter methods for Purchase properties. 
     */
    protected static $setters = ['status'];

    /**
     * Purchase constructor.
     *
     * @param int $user_id       The ID of the user making the purchase.
     * @param string|null $purchased_at The timestamp of when the purchase was made (optional).
     * @param string|null $delivered_at  The timestamp of when the purchase was delivered (optional).
     * @param string $status      The status of the purchase (default is 'pending').
     * @param int|null $id       The ID of the purchase (optional).
     */
    public function __construct($user_id, $purchased_at = null, $delivered_at = null, $status = 'pending', $id = null)
    {
        $this->id = $id; 
        $this->user_id = $user_id;
        $this->purchased_at = $purchased_at;
        $this->delivered_at = $delivered_at;
        $this->status = $status;
    }
}
