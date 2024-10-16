<?php

namespace app\models;

use app\models\BaseModel;

/**
 * Class User
 *
 * Represents a user entity with properties and methods for managing user data.
 *
 * @method int id()                          Retrieves the unique identifier of the user.
 * @method string email()                    Retrieves the email address of the user.
 * @method string hashed_password()          Retrieves the hashed password of the user.
 * @method string first_name()               Retrieves the first name of the user.
 * @method string last_name()                Retrieves the last name of the user.
 * @method string street()                   Retrieves the street name of the user's address.
 * @method string str_no()                   Retrieves the street number of the user's address.
 * @method string zip()                      Retrieves the zip code of the user's address.
 * @method string city()                     Retrieves the city of the user's address.
 * @method string role()                     Retrieves the role of the user.
 * @method void role(string $role)           Sets the role of the user.
 */
class User extends BaseModel
{
    private $id;
    private $email;
    private $hashed_password;
    private $first_name;
    private $last_name;
    private $street;
    private $str_no;
    private $zip;
    private $city;
    private $role;

    /**
     * @var array List of getter methods for User properties. 
     */
    protected static $getters = ['id', 'email', 'hashed_password', 'first_name', 'last_name', 'street', 'str_no', 'zip', 'city', 'role'];

    /**
     * @var array List of setter methods for User properties. 
     */
    protected static $setters = ['role'];

    /**
     * User constructor.
     *
     * @param string      $email           The email address of the user.
     * @param string      $hashed_password The hashed password of the user.
     * @param string      $first_name      The first name of the user.
     * @param string      $last_name       The last name of the user.
     * @param string      $street          The street name of the user's address.
     * @param string      $str_no          The street number of the user's address.
     * @param string      $zip             The zip code of the user's address.
     * @param string      $city            The city of the user's address.
     * @param int|null    $id              The unique identifier of the user (optional).
     * @param string|null $role            The user role ['customer', 'admin']. Defaults in DB to 'customer'.
     */
    public function __construct($email, $hashed_password, $first_name, $last_name, $street, $str_no, $zip, $city, $id = null, $role = null)
    {
        $this->id = $id; 
        $this->email = $email;
        $this->hashed_password = $hashed_password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->street = $street;
        $this->str_no = $str_no;
        $this->zip = $zip;
        $this->city = $city;
        $this->role = $role;
    }

    /**
     * Checks if the current user is an admin.
     *
     * @return bool True if the user is an admin, false otherwise.
     */
    public static function isAdmin(): bool 
    {
        $userId = $_SESSION['login'];
        $user = User::findBy($userId, 'id');
        return $user->role() === 'admin';
    }
}
