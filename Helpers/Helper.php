<?php
class Helper
{
    // CSRF-Token überprüfen
    public static function checkCSRFToken(): void
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // Token ungültig oder nicht vorhanden
            die('Ungültiger CSRF-Token');
        }
    }

    public static function generateCSRFToken(): string
    {
        // Session starten
        session_status() === PHP_SESSION_NONE && session_start();
        // CSRF-Token generieren, falls es nicht bereits existiert
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}
