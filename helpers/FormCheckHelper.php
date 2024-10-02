<?php

require_once __DIR__ . '/../core/BaseClass.php';

/**
 * FormCheckHelper class for handling form data, especially related to password validation.
 * 
 * This class extends BaseClass and provides mechanisms to validate passwords against a 
 * defined policy and check the equality of passwords during registration.
 *
 * Properties:
 * 
 * - $email: Stores the user's email address.
 * - $password: Stores the original password entered by the user.
 * - $password_hash: Stores the hashed version of the password.
 * - $password_repeat: Stores the repeated password for confirmation.
 *
 * Static Properties:
 * 
 * - $noGetters: Disallows access to the 'password_repeat' property via a getter.
 * - $noSetters: Disallows setting the 'password_hash' property via a setter.
 *
 * Methods:
 * 
 * @method __construct(array $formData) Initializes the class with form data, setting 
 *        email, password, and confirm_password (if provided).
 * 
 * @method bool validatePasswordPolicy() Validates the password against a security policy
 *        requiring a minimum length, uppercase, lowercase, digits, and special characters.
 * 
 * @method bool validatePasswordEquality() Verifies that the repeated password matches the hashed password.
 * 
 * @method void setHashedPassword(string $password) Hashes the given password using the 
 *        default algorithm and stores it in the $password_hash property.
 *
 * @method string email()
 * @method string password()
 * @method string password_repeat()
 * @method string password_hash()
 */
class FormCheckHelper extends BaseClass
{
    private $email;
    private $password;
    private $password_hash;
    private $password_repeat;

    protected static $getters = [ 'email', 'password', 'password_hash' ];
    protected static $setters = [ 'email', 'password', 'password_repeat' ];

    /**
     * Constructor that initializes the form data.
     * 
     * @param array $formData Data from the form including email, password, and confirmation password.
     */
    public function __construct(array $formData)
    {
        // Check if 'email' key is set and not null, then assign it to the object property
        isset($formData['email']) && $this->email($formData['email']);
        
        // Store the original password (according to the password policy)
        if (isset($formData['password'])) {
            $password = $formData['password'];
            $this->password($password);
            $this->setHashedPassword($password); 
        }
        isset($formData['confirm_password']) && $this->password_repeat($formData['confirm_password']);
    }

    /**
     * Validates the password against security policy requirements.
     * 
     * @return bool True if the password meets the criteria, false otherwise.
     */
    public function validatePasswordPolicy(): bool
    {
        // Regex to check password strength
        // minimum length should be 8: {8,}
        // at least one uppercase letter: [A-Z]
        // at least one lowercase letter: [a-z]
        // at least one digit: \d
        // at least one special character: [\W_]
        // ?= matches without consuming
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

        // Use PHP function preg_match
        if (preg_match($pattern, $this->password)) {
            $this->password(null);
            return true; 
        }

        return false;
    }

    /**
     * Validates that the repeated password matches the original password.
     * 
     * @return bool True if the passwords match, false otherwise.
     */
    public function validatePasswordEquality(): bool
    {
        // Use PHP function password_verify
        // here: negated by "!" at the beginning
        if (!password_verify($this->password_repeat, $this->password_hash)) {
            return false;
        }

        $this->password_repeat(null);
        return true;
    }

    /**
     * Hashes the given password and stores it in the $password_hash property.
     * 
     * @param string $password The password to hash.
     */
    private function setHashedPassword(string $password): void
    {
        // Hash Password with default value according to:
        // https://www.php.net/manual/de/function.password-hash.php
        // and benchmarked costs according to Example #3
        $hashed_password = password_hash($password, PASSWORD_DEFAULT, ["cost" => 12]);
        $this->password_hash = $hashed_password;
    }
}
