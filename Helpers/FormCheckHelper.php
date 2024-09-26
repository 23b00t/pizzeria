<?php

class FormCheckHelper
{
    // Klassen Eigenschaften deklarieren
    // Diese Eigenschaften hat dann auch das instanzierte Objekt der Klasse FormCheck 
    private $name;
    private $password;
    private $password_hash;
    private $password_repeat;

    // DER KONSTRUKTOR, hier werden Bedürfnisse formuliert, 
    // die mit der Objekt-Instanz gleich mit konstruiert werden
    public function __construct($formData)
    {
        // Fallunterscheidung: 
        // ist die Schlüsselstelle 'name', 'pw' oder 'pw_repeat' 
        // im Superglobalen Formular-Daten-Array: $_POST ?
        // Wenn ja, dann weise den Wert der Schlüsselstelle der Objekt-Eigenschaft zu
        // z.B.: 
        // WENN die Schlüsselstelle 'name' gesetzt ist UND der Wert sich von NULL unterscheidet,
        // DANN weise den Wert der Schlüsselstelle 'name' der Objekt-Eigenschaft ($this-Eigenschaft) name zu
        isset($formData['username']) && $this->setName($formData['username']);
        
        // Das ursprüngliche Passwort (zweckgebunden an der Passwortrichtlinie) 
        // einmalig speichern. 
        // WENN der Zweck erfüllt ist, die Objekt-Eigenschaft zurücksetzen, z.b.: null         { 
        if (isset($formData['password'])) 
        {
            $password = $formData['password'];
            $this->setPassword($password);
            $this->setHashedPassword($password); 
        }
        isset($formData['confirm_password']) && $this->setRepeatPassword($formData['confirm_password']);
        print_r($this->password);
    }

    // Methode: validierePasswortRichtlinie
    // Zweck: PasswortBestandteile, gemäß Passwort-Richtlinie auf Vorhandenheit prüfen
    // Rückgabewert: BOOLEAN true/false
    public function validatePasswordPolicy()
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
    public function validatePasswordEquality()
    {
        // PHP Funktion password_verify, siehe Referenz php.net
        // hier: durch "!" am Anfang negiert - verneint - nichtzutreffen

        if (!password_verify($this->password_repeat, $this->password_hash)) {
            return false;
        }

        $this->setRepeatPassword(null);
        return true;
    }
    
    private function setName($name)
    {
        $this->name = $name;
    }

    private function setPassword($password)
    {
        $this->password = $password;
    }

    private function setHashedPassword($password)
    {
        // Hash Password with default value according to:
        // https://www.php.net/manual/de/function.password-hash.php
        // and benchmarked costs according to Beispiel #3
        $password_hashed = password_hash($password, PASSWORD_DEFAULT, ["cost" => 12]);
        $this->password_hash = $password_hashed;
    }

    private function setRepeatPassword($password)
    {
        $this->password_repeat = $password;
    }

    public function getUsername()
    {
        return $this->name;
    }

    public function getHashedPassword()
    {
        return $this->password_hash;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
?>
