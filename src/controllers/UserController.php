<?php

namespace app\controllers;

use app\models\User;
use app\helpers\FormCheckHelper;
use PDOException;

/**
 * UserController class responsible for managing user-related actions, such as
 * displaying user profiles, handling user login, and processing user registration.
 */
class UserController
{
    private string $area;
    private string $action;
    private string $view;
    private bool $redirect;
    private string $msg;

    /**
     * @param string $area
     * @param string $action
     * @param string $view
     * @param bool $redirect
     * @param string $msg
     */
    public function __construct(string &$area, string &$action, string &$view, bool &$redirect, string &$msg)
    {
        $this->area = &$area;
        $this->action = &$action;
        $this->view = &$view;
        $this->redirect = &$redirect;
        $this->msg = &$msg;
    }

    /**
     * showLogin
     *
     * @return array
     */
    public function showLogin(): void
    {
        $this->view = 'user/login_form';
    }

    /**
     * showRegister
     *
     * @return array
     */
    public function new(): void
    {
        $this->view = 'user/register_form';
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
    public function login(array $formData): void
    {
        $formCheckHelper = new FormCheckHelper($formData);
        $email = $formCheckHelper->email();
        $user = User::findBy($email, 'email');

        if ($user && password_verify($formCheckHelper->password(), $user->hashed_password())) {
            // save user id to session to authenticate it
            $_SESSION['login'] = $user->id();
            $this->redirect = true;
            $this->area = 'pizza';
            $this->action = 'index';
        } else {
            // Failed login
            $this->view = 'user/login_form';
            $this->msg = 'error=Login failed';
        }
    }

    /**
     * Handle the user registration process.
     *
     * This method validates the registration form data, checking for password equality
     * and policy compliance. If validation passes, a new user object is created
     * and stored in the database.
     * TODO: Inconsistent use of create method
     *
     * @param array $formData The form data submitted for registration.
     * @return array
     */
    public function create(array $formData): void
    {
        $formCheckHelper = new FormCheckHelper($formData);

        if (!$formCheckHelper->validatePasswordEquality()) {
            $this->view = 'user/register_form';
            $this->msg = 'error=Passwords do not match';
        } elseif (!$formCheckHelper->validatePasswordPolicy()) {
            $this->view = 'user/register_form';
            $this->msg = 'error=Weak password';
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
    private function store(User $user): void
    {
        try {
            // Try to save the user
            $user->save();

            // Successful insertion
            $this->view = 'user/login_form';
            $this->msg = 'msg=Account successfully created';
        } catch (PDOException $e) {
            // Error 23000: Duplicate entry (database error for UNIQUE constraint)
            if ($e->getCode() === '23000') {
                $this->view = 'user/register_form';
                $this->msg = 'error=Username not available';
            } else {
                // Other errors
                $this->view = 'user/register_form';
                $this->msg = 'error=Unknown error';
            }
        }
    }

    /**
     * Log out the user.
     *
     * This method clears the session and redirects the user to the index page.
     * @return array
     */
    public function signOut(): void
    {
        session_unset();
        session_destroy();
        $this->view = 'user/login_form';
    }
}
