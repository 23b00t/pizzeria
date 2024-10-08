<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/BaseModel.php';

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
 *
 * @method int id() 
 * @method string email()
 * @method string hashed_password()
 * @method string first_name()
 * @method string last_name()
 * @method string street()
 * @method string str_no()
 * @method string zip()
 * @method string city()
 * @method string role()
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

    protected static $getters = ['id', 'email', 'hashed_password', 'first_name', 'last_name', 'street', 'str_no', 'zip', 'city', 'role'];

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
     * @param string|null $role            The user role ['customer', 'admin']. Defaults in DB to 'customer';
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

    public static function isAdmin() {
        $userId = $_SESSION['login'];
        $user = User::findBy($userId, 'id');
        return $user->role() === 'admin';
    }
}
