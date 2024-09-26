<?php

require_once __DIR__ . '/../Helpers/DatabaseHelper.php';

/**
 * User class
 */

class User
{
    private $id;
    private $email;
    private $hashed_password;
    private $first_name;
    private $last_name;
    private $address;

    public function __construct($email, $hashed_password, $first_name, $last_name, $address, $id = null)
    {
        $this->id = $id; 
        $this->email = $email;
        $this->hashed_password = $hashed_password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->address = $address;
    }

    public function save()
    {
        // Verbindung zur Datenbank herstellen
        $db = new DatabaseHelper("writer", "writer_password");

        // SQL-Abfrage und Parameter definieren
        $sql = 'INSERT INTO user (email, hashed_password, first_name, last_name, address) VALUES (?, ?, ?, ?, ?)';
        $params = [$this->email, $this->hashed_password, $this->first_name, $this->last_name, $this->address];  

        // Benutzer in die Datenbank einfügen
        return $db->prepareAndExecute($sql, $params);  
    }

    public static function findByEmail($email)
    {
        $db = new DatabaseHelper("reader", "reader_password");

        $sql = 'SELECT * FROM user WHERE email = ?';
        $params = [$email];

        $result = $db->prepareAndExecute($sql, $params);

        if ($result) {
            $userData = $result[0];
            return new User($userData['email'], $userData['hashed_password'], $userData['first_name'], $userData['last_name'], $userData['address'], $userData['id']);
        }

        return null;
    }

    public static function findById($id)
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

    public function getEmail()
    {
        return $this->email;
    }

    public function getHashedPassword()
    {
        return $this->hashed_password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getAddress()
    {
        return $this->address;
    }
}
