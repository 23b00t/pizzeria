<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

/**
 * Class Purchase
 *
 * Represents a purchase entity with properties and methods for database interactions.
 *
 * @method int id()
 * @method string status() inherited from BaseClass
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
    protected static $getters = ['id', 'status', 'user_id', 'purchased_at', 'delivered_at'];

    /**
     * @var array List of setter methods for Purchase properties. 
     */
    protected static $setters = ['status'];

    /**
     * Purchase constructor.
     *
     * @param int $id;
     * @param int $user_id;
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
