<?php

require_once __DIR__ . '/../Helpers/DatabaseHelper.php';
require_once __DIR__ . '/../Helpers/FormCheckHelper.php';

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
     * @param int $id The user ID.
     */
    public function show($id): void
    {
        $user = User::findById($id);

        if ($user) {
            // Include the user profile view and pass the user object
            include './Views/user_profile.php';
        } else {
            echo "User not found.";
        }
    }

    /**
     * Handle the user login process.
     * 
     * @param array $formData The form data submitted for login.
     */
    public function login($formData): void
    {
        $formCheckHelper = new FormCheckHelper($formData);
        $user = User::findByEmail($formCheckHelper->email());

        if ($user && password_verify($formCheckHelper->password(), $user->hashed_password())) {
            // Successful login, start session
            // session_start();
            $_SESSION["login"] = "true";
            header('Location: ./index.php?user_id=' . $user->id());
            exit();
        } else {
            // Failed login
            header('Location: ./Views/login_form.php?error=Invalid%20login%20credentials');
            exit();
        }
    }

    /**
     * Handle the user registration process.
     * 
     * @param array $formData The form data submitted for registration.
     */
    public function create($formData): void 
    {
        $formCheckHelper = new FormCheckHelper($formData);

        if (!$formCheckHelper->validatePasswordEquality()) {
            header('Location: ./Views/register_form.php?error=Passwords%20do%20not%20match');
            exit();
        } elseif (!$formCheckHelper->validatePasswordPolicy()) {
            header('Location: ./Views/register_form.php?error=Weak%20password');
            exit();
        } else {
            // Create a new user object
            $user = new User($formCheckHelper->email(), $formCheckHelper->password_hash(), $formData['first_name'], $formData['last_name'], $formData['address']);
            // Save the new user to the database
            $this->store($user);
        }
    }

    /**
     * Store a new user in the database.
     * 
     * @param User $user The user object to be stored.
     */
    private function store($user): void
    {
        try {
            // Try to save the user
            $user->save();

            // Successful insertion
            header('Location: ./Views/login_form.php?msg=Account%20successfully%20created');
            exit();
        } catch (PDOException $e) {
            // Error 1062: Duplicate entry (database error for UNIQUE constraint)
            if ($e->getCode() === '23000') { 
                header('Location: ./Views/register_form.php?error=Username%20not%20available');
            } else {
                // Other errors
                header('Location: ./Views/register_form.php?error=Unknown%20error');
            }
            exit();
        }
    }

    /**
     * Log out the user.
     */
    public static function signOut(): void
    {
        session_unset();
        session_destroy();
        header("Location: ./index.php");
        exit();
    }
}
