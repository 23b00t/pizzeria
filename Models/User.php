<?php

require_once __DIR__ . '/../Helpers/DatabaseHelper.php';
require_once __DIR__ . '/../BaseClass.php';

/**
 * User class responsible for representing and managing user data.
 * 
 * Properties:
 * 
 * - $id: The unique identifier of the user.
 * - $email: The email address of the user.
 * - $hashed_password: The hashed password of the user.
 * - $first_name: The first name of the user.
 * - $last_name: The last name of the user.
 * - $address: The address of the user.
 * - $role: The role of the user.
 */
class User extends BaseClass
{
    private $id;
    private $email;
    private $hashed_password;
    private $first_name;
    private $last_name;
    private $address;
    private $role;

    protected static $getters = ['id', 'email', 'hashed_password', 'first_name', 'last_name', 'address', 'role'];

    /**
     * User constructor.
     *
     * @param string      $email           The email address of the user.
     * @param string      $hashed_password The hashed password of the user.
     * @param string      $first_name      The first name of the user.
     * @param string      $last_name       The last name of the user.
     * @param string      $address         The address of the user.
     * @param int|null    $id              The unique identifier of the user (optional).
     * @param string|null $role            The user role ['customer', 'admin']. Defaults in DB to 'customer';
     */
    public function __construct($email, $hashed_password, $first_name, $last_name, $address, $id = null, $role = null)
    {
        $this->id = $id; 
        $this->email = $email;
        $this->hashed_password = $hashed_password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->address = $address;
        $this->role = $role;
    }

    /**
     * Saves the current user object to the database.
     *
     * @return array The result of the database operation.
     */
    public function save(): array
    {
        // Establish database connection
        $db = new DatabaseHelper("writer", getenv('PW_WRITER'));

        // Define SQL query and parameters
        $sql = 'INSERT INTO user (email, hashed_password, first_name, last_name, address) VALUES (?, ?, ?, ?, ?)';
        $params = [$this->email, $this->hashed_password, $this->first_name, $this->last_name, $this->address];  

        // Insert the user into the database
        return $db->prepareAndExecute($sql, $params);  
    }

    /**
     * Finds a user by their email address.
     *
     * @param  string $email The email address of the user to find.
     * @return User|null The User object if found, null otherwise.
     */
    public static function findByEmail($email): ?User
    {
        // Establish database connection
        $db = new DatabaseHelper("reader", getenv('PW_READER'));

        // Define SQL query and parameters
        $sql = 'SELECT * FROM user WHERE email = ?';
        $params = [$email];

        // Execute the query
        $result = $db->prepareAndExecute($sql, $params);

        if ($result) {
            $userData = $result[0];
            return new User($userData['email'], $userData['hashed_password'], $userData['first_name'], $userData['last_name'], $userData['address'], $userData['id']);
        }

        return null;
    }

    /**
     * Finds a user by their ID.
     *
     * @param  int $id The ID of the user to find.
     * @return User|null The User object if found, null otherwise.
     */
    public static function findById($id): ?User
    {
        // Establish database connection
        $db = new DatabaseHelper("reader", "reader_password");
        
        // Define SQL query and parameters
        $sql = 'SELECT * FROM user WHERE id = ?';
        $params = [$id];

        // Execute the query
        $result = $db->prepareAndExecute($sql, $params);

        if ($result) {
            $userData = $result[0];
            return new User($userData['email'], $userData['hashed_password'], $userData['first_name'], $userData['last_name'], $userData['address'], $userData['id']);
        }

        return null;
    }
}
