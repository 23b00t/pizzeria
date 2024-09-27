<?php

require_once __DIR__ . '/../BaseClass.php';

class FormCheckHelper extends BaseClass
{
    // Klassen Eigenschaften deklarieren
    // Diese Eigenschaften hat dann auch das instanzierte Objekt der Klasse FormCheck 
    private $email;
    private $password;
    private $password_hash;
    private $password_repeat;

	protected static $noGetters = ['password_repeat'];
    protected static $noSetters = ['password_hash'];

    // DER KONSTRUKTOR, hier werden Bedürfnisse formuliert, 
    // die mit der Objekt-Instanz gleich mit konstruiert werden
    public function __construct($formData)
    {
        // WENN die Schlüsselstelle 'email' gesetzt ist UND der Wert sich von NULL unterscheidet,
        // DANN weise den Wert der Schlüsselstelle 'email' der Objekt-Eigenschaft ($this-Eigenschaft) email zu
        isset($formData['email']) && $this->email($formData['email']);
        
        // Das ursprüngliche Passwort (zweckgebunden an der Passwortrichtlinie) 
        // einmalig speichern. 
        // WENN der Zweck erfüllt ist, die Objekt-Eigenschaft zurücksetzen, z.b.: null 
        if (isset($formData['password'])) 
        {
            $password = $formData['password'];
            $this->password($password);
            $this->setHashedPassword($password); 
        }
        isset($formData['confirm_password']) && $this->password_repeat($formData['confirm_password']);
    }

    // Methode: validierePasswortRichtlinie
    // Zweck: PasswortBestandteile, gemäß Passwort-Richtlinie auf Vorhandenheit prüfen
    // Rückgabewert: BOOLEAN true/false
    public function validatePasswordPolicy(): bool
    {
        // Regex to check password strength
        // minimum length should be 8: {8,}
        // at least one uppercase letter: [A-Z]
        // at least one lowercase letter: [a-z]
        // at least one digits: \d
        // at least one special character: [\W_]
        // ?= matches without consuming
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

        // PHP Funktion preg_match, siehe Referenz php.net
        if (preg_match($pattern, $this->password)) {
            $this->setPassword(null);
            return true; 
        }

        return false;
    }

    // Methode: validierePasswortGleichheit
    // Zweck: Bei der Registrierung die beiden Passwort-Eingaben auf Gleichheit prüfen, 
    //        ob sie übereinstimmen
    // Rückgabewert: BOOLEAN true/false
    public function validatePasswordEquality(): bool
    {
        // PHP Funktion password_verify, siehe Referenz php.net
        // hier: durch "!" am Anfang negiert - verneint - nichtzutreffen
        if (!password_verify($this->password_repeat, $this->password_hash)) {
            return false;
        }

        $this->setRepeatPassword(null);
        return true;
    }

    private function setHashedPassword($password): void
    {
        // Hash Password with default value according to:
        // https://www.php.net/manual/de/function.password-hash.php
        // and benchmarked costs according to Beispiel #3
        $hashed_password = password_hash($password, PASSWORD_DEFAULT, ["cost" => 12]);
        $this->password_hash = $hashed_password;
    }
}
