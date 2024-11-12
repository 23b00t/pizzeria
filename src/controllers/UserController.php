<?php

namespace app\controllers;

use app\core\Response;
use app\models\User;
use app\helpers\FormCheckHelper;
use PDOException;

/**
 * UserController class responsible for managing user-related actions, such as
 * displaying user profiles, handling user login, and processing user registration.
 */
class UserController extends BaseController
{
    /**
     * index
     *
     * @return Response
     */
    public function index(): Response
    {
        return new Response([], 'user/login_form');
    }

    /**
     * showRegister
     *
     * @return Response
     */
    public function new(): Response
    {
        return new Response([], 'user/register_form');
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
     * @return Response
     */
    public function login(array $formData): Response
    {
        $formCheckHelper = new FormCheckHelper($formData);
        $email = $formCheckHelper->email();
        $user = User::findBy($email, 'email');

        if ($user && password_verify($formCheckHelper->password(), $user->hashed_password())) {
            // save user id to session to authenticate it
            $_SESSION['login'] = $user->id();
            $response = (new PizzaController())->index();
        } else {
            // Failed login
            $response = $this->index();
            $response->setMsg('error=Login failed');
        }
        return $response;
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
     * @return Response
     */
    public function create(array $formData): Response
    {
        $formCheckHelper = new FormCheckHelper($formData);

        if (!$formCheckHelper->validatePasswordEquality()) {
            $response = $this->new();
            $response->setMsg('error=Passwörter stimmen nicht überein');
        } elseif (!$formCheckHelper->validatePasswordPolicy()) {
            $response = $this->new();
            $response->setMsg('error=Passwort zu schwach');
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
            $response = $this->store($user);
        }
        return $response;
    }

    /**
     * Store a new user in the database.
     *
     * This method attempts to save the user object to the database.
     * If successful, the user is redirected to the login form with a success message.
     * If an error occurs, it handles database exceptions, particularly for unique constraints.
     *
     * @param User $user The user object to be stored.
     * @return Response
     */
    private function store(User $user): Response
    {
        try {
            // Try to save the user
            $user->save();

            // Successful insertion
            $response = $this->index();
            $response->setMsg('msg=Account successfully created');
        } catch (PDOException $e) {
            // Error 23000: Duplicate entry (database error for UNIQUE constraint)
            if ($e->getCode() === '23000') {
                $response = $this->new();
                $response->setMsg('error=Username not available');
            } else {
                // Other errors
                $response = $this->index();
                $response->setMsg('error=Unknown error');
            }
        }
        return $response;
    }

    /**
     * Log out the user.
     *
     * This method clears the session and redirects the user to the index page.
     * @return Response
     */
    public function signOut(): Response
    {
        session_unset();
        session_destroy();
        return $this->index();
    }
}
