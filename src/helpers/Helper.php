<?php

namespace app\helpers;

/**
 * Helper class providing utility functions for CSRF (Cross-Site Request Forgery) protection.
 * 
 * This class includes methods to check and generate CSRF tokens to help prevent 
 * CSRF attacks in web applications. It ensures that requests made to the server 
 * are legitimate and originate from the same session.
 * 
 * Methods:
 * 
 * @method static void checkCSRFToken() 
 *         Checks if the CSRF token in the POST request is valid. If the token is 
 *         invalid or not present, it terminates the script with an error message.
 * 
 * @method static string generateCSRFToken() 
 *         Generates a new CSRF token if one does not already exist in the session. 
 *         Returns the current CSRF token.
 */
class Helper
{
    /**
     * Checks the validity of the CSRF token in the current request.
     * 
     * @throws Exception If the CSRF token is invalid or not present.
     */
    public static function checkCSRFToken(): void
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // Invalid or missing token
            die('Invalid CSRF token');
        }
    }

    /**
     * Generates a new CSRF token and stores it in the session if one does not 
     * already exist.
     * 
     * @return string The current CSRF token.
     */
    public static function generateCSRFToken(): string
    {
        // Start session if it hasn't already
        session_status() === PHP_SESSION_NONE && session_start();
        // Generate CSRF token if it doesn't already exist
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function validateSession(): void
    {
        // https://stackoverflow.com/questions/22965067/when-and-why-i-should-use-session-regenerate-id#22965580
        if (!isset($_SESSION['login'])) {
            header('Location: ./index.php');
            exit();
        } else {
            session_regenerate_id(true);
        }
    }
}
