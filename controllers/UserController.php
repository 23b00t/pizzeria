<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/../helpers/FormCheckHelper.php';

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
     * Show the user profile page.
     *
     * This method retrieves the user by the specified ID and includes the view
     * for displaying the user profile. If the user is not found, an error message
     * is displayed.
     *
     * @param int $id The user ID.
     */
    public function show(int $id): void
    {
        $user = User::findBy($id, 'id');

        if ($user) {
            // Include the user profile view and pass the user object
            include './views/user/user_profile.php';
        } else {
            echo 'User not found.';
        }
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
     */
    public function login(array $formData): void
    {
        $formCheckHelper = new FormCheckHelper($formData);
        $email = $formCheckHelper->email();
        $user = User::findBy($email, 'email');

        if ($user && password_verify($formCheckHelper->password(), $user->hashed_password())) {

            // save user id to session to authenticate it
            $_SESSION['login'] = $user->id();
            header('Location: ./index.php?pizza/index');
            exit();
        } else {
            // Failed login
            header('Location: ./views/user/login_form.php?error=Invalid%20login%20credentials');
            exit();
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
     */
    public function create(array $formData): void
    {
        $formCheckHelper = new FormCheckHelper($formData);

        if (!$formCheckHelper->validatePasswordEquality()) {
            header('Location: ./views/user/register_form.php?error=Passwords%20do%20not%20match');
            exit();
        } elseif (!$formCheckHelper->validatePasswordPolicy()) {
            header('Location: ./views/user/register_form.php?error=Weak%20password');
            exit();
        } else {
            // Create a new user object
            $user = new User($formCheckHelper->email(), $formCheckHelper->password_hash(), $formData['first_name'], $formData['last_name'], $formData['street'], $formData['str_no'], $formData['zip'], $formData['city']);

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
     */
    private function store(User $user): void
    {
        try {
            // Try to save the user
            $user->save();

            // Successful insertion
            header('Location: ./views/user/login_form.php?msg=Account%20successfully%20created');
            exit();
        } catch (PDOException $e) {
            // Error 23000: Duplicate entry (database error for UNIQUE constraint)
            if ($e->getCode() === '23000') {
                header('Location: ./views/user/register_form.php?error=Username%20not%20available');
            } else {
                // Other errors
                header('Location: ./views/user/register_form.php?error=Unknown%20error%20' . $e->getCode());
            }
            exit();
        }
    }

    /**
     * Log out the user.
     *
     * This method clears the session and redirects the user to the index page.
     */
    public static function signOut(): void
    {
        session_unset();
        session_destroy();
        header('Location: ./index.php');
        exit();
    }
}
