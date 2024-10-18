<?php

namespace app\controllers;

use app\models\User;
use app\helpers\FormCheckHelper;
use PDOException;

/**
 * UserController class responsible for managing user-related actions, such as
 * displaying user profiles, handling user login, and processing user registration.
 *
 * Methods:
 *
 * - show(int $id): void: Displays the user profile based on the given ID.
 * - login(array $formData): void: Processes user login with the provided form data.
 * - create(array $formData): void: Validates and creates a new user from the provided form data.
 * - store(User $user): void: Saves the user object to the database.
 * - signOut(): void: Logs out the user by clearing the session.
 */
class UserController
{
    /**
     * showLogin
     *
     * @return array
     */
    public function showLogin(): array
    {
        return ['view' => 'user/login_form'];
    }

    /**
     * showRegister
     *
     * @return array
     */
    public function new(): array
    {
        return ['view' => 'user/register_form'];
    }

    /**
     * Handle the user login process.
     *
     * This method validates the login credentials from the submitted form data.
     * If the credentials are valid, the user ID is saved in the session,
     * and the user is redirected to the pizza index. Otherwise, an error message
     * is displayed on the login form.
     *
     * @param array $formData The form data submitted for login.
     * @return array
     */
    public function login(array $formData): array
    {
        $formCheckHelper = new FormCheckHelper($formData);
        $email = $formCheckHelper->email();
        $user = User::findBy($email, 'email');

        if ($user && password_verify($formCheckHelper->password(), $user->hashed_password())) {
            // save user id to session to authenticate it
            $_SESSION['login'] = $user->id();
            return [ 'redirect' => 'true', 'area' => 'pizza', 'action' => 'index'];
            exit();
        } else {
            // Failed login
            return ['view' => 'user/login_form', 'msg' => '?error=Login%20failed'];
        }
    }

    /**
     * Handle the user registration process.
     *
     * This method validates the registration form data, checking for password equality
     * and policy compliance. If validation passes, a new user object is created
     * and stored in the database.
     *
     * @param array $formData The form data submitted for registration.
     * @return array
     */
    public function create(array $formData): array
    {
        $formCheckHelper = new FormCheckHelper($formData);

        if (!$formCheckHelper->validatePasswordEquality()) {
            return ['view' => 'user/register_form', 'msg' => '?error=Passwords%20do%20not%20match'];
        } elseif (!$formCheckHelper->validatePasswordPolicy()) {
            return ['view' => 'user/register_form', 'msg' => '?error=Weak%20password'];
        } else {
            // Create a new user object
            $user = new User(
                $formCheckHelper->email(),
                $formCheckHelper->password_hash(),
                $formData['first_name'],
                $formData['last_name'],
                $formData['street'],
                $formData['str_no'],
                $formData['zip'],
                $formData['city']
            );

            // Save the new user to the database
            $this->store($user);
            return [];
        }
    }

    /**
     * Store a new user in the database.
     *
     * This method attempts to save the user object to the database.
     * If successful, the user is redirected to the login form with a success message.
     * If an error occurs, it handles database exceptions, particularly for unique constraints.
     *
     * @param User $user The user object to be stored.
     * @return array
     */
    private function store(User $user): array
    {
        try {
            // Try to save the user
            $user->save();

            // Successful insertion
            return ['view' => 'user/login_form', 'msg' => '?msg=Account%20successfully%20created'];
        } catch (PDOException $e) {
            // Error 23000: Duplicate entry (database error for UNIQUE constraint)
            if ($e->getCode() === '23000') {
                return ['view' => 'user/register_form', 'msg' => '?error=Username%20not%20available'];
            } else {
                // Other errors
                return ['view' => 'user/register_form', 'msg' => '?error=Unknown%20error%20'];
            }
        }
    }

    /**
     * Log out the user.
     *
     * This method clears the session and redirects the user to the index page.
     * @return array
     */
    public function signOut(): array
    {
        session_unset();
        session_destroy();
        return ['view' => 'user/login_form'];
    }
}
