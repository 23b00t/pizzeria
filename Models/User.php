<?php

require_once __DIR__ . '/../Helpers/DatabaseHelper.php';
require_once __DIR__ . '/../BaseClass.php';

/**
 * User class
 */

class User extends BaseClass
{
    private $id;
    private $email;
    private $hashed_password;
    private $first_name;
    private $last_name;
    private $address;

	protected static $noSetters = ['id', 'email', 'hashed_password', 'first_name', 'last_name', 'address'];

    public function __construct($email, $hashed_password, $first_name, $last_name, $address, $id = null)
    {
        $this->id = $id; 
        $this->email = $email;
        $this->hashed_password = $hashed_password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->address = $address;
    }

    public function save(): array
    {
        // Verbindung zur Datenbank herstellen
        $db = new DatabaseHelper("writer", getenv('PW_WRITER'));

        // SQL-Abfrage und Parameter definieren
        $sql = 'INSERT INTO user (email, hashed_password, first_name, last_name, address) VALUES (?, ?, ?, ?, ?)';
        $params = [$this->email, $this->hashed_password, $this->first_name, $this->last_name, $this->address];  

        // Benutzer in die Datenbank einfügen
        return $db->prepareAndExecute($sql, $params);  
    }

    public static function findByEmail($email): ?User
    {
        $db = new DatabaseHelper("reader", getenv('PW_READER'));

        $sql = 'SELECT * FROM user WHERE email = ?';
        $params = [$email];

        $result = $db->prepareAndExecute($sql, $params);

        if ($result) {
            $userData = $result[0];
            return new User($userData['email'], $userData['hashed_password'], $userData['first_name'], $userData['last_name'], $userData['address'], $userData['id']);
        }

        return null;
    }

    public static function findById($id): ?User
    {
        $db = new DatabaseHelper("reader", "reader_password");
        $sql = 'SELECT * FROM user WHERE id = ?';
        $params = [$id];

        $result = $db->prepareAndExecute($sql, $params);

        if ($result) {
            $userData = $result[0];
            return new User($userData['email'], $userData['hashed_password'], $userData['first_name'], $userData['last_name'], $userData['address'], $userData['id']);
        }

        return null;
    }
}
