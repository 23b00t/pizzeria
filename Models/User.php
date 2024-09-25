<?php

require_once __DIR__ . '/../Helpers/DatabaseHelper.php';

/**
 * User class
 */

class User
{
    private $id;
    private $username;
    private $password_hashed;

    public function __construct($username, $password_hashed, $id = null)
    {
        $this->username = $username;
        $this->password_hashed = $password_hashed;
        $this->id = $id; 
    }

    public function save()
    {
        // Verbindung zur Datenbank herstellen
        $db = new DatabaseHelper("user_write", "password_write");

        // SQL-Abfrage und Parameter definieren
        $sql = 'INSERT INTO user (username, password) VALUES (?, ?)';
        $params = ['ss', $this->username, $this->password_hashed];  // 'ss' steht für zwei Strings

        // Benutzer in die Datenbank einfügen
        return $db->prepareAndExecute($sql, $params);  
    }

    public static function findByUsername($username)
    {
        $db = new DatabaseHelper("user_read", "password");

        $sql = 'SELECT * FROM user WHERE username = ?';
        $params = ['s', $username];

        $result = $db->prepareAndExecute($sql, $params);
        $userData = $result->fetch_assoc();

        if ($userData) {
            return new User($userData['username'], $userData['password'], $userData['id']);
        }

        return null;
    }

    public static function findById($id)
    {
        // SQL-Abfrage zum Abrufen des Benutzers nach ID
        $db = new DatabaseHelper("user_read", "password");
        $sql = 'SELECT * FROM user WHERE id = ?';
        $params = ['i', $id];  // 'i' steht für einen Integer

        $result = $db->prepareAndExecute($sql, $params);
        $userData = $result->fetch_assoc();

        if ($userData) {
            return new User($userData['username'], $userData['password'], $userData['id']);
        }

        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password_hashed;
    }

    public function getId()
    {
        return $this->id;
    }
}
