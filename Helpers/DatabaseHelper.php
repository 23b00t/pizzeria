<?php

class DatabaseHelper
{
    private $_conn;

    public function __construct($dbuser, $dbpassword)
    {
        $servername = "127.0.0.1";
        $dbname = "pizza";

        // Verbindung herstellen
        $this->_conn = new mysqli($servername, $dbuser, $dbpassword, $dbname);

        // Verbindung prüfen
        if ($this->_conn->connect_error) {
            die("Verbindung fehlgeschlagen: " . $this->_conn->connect_error . "\n");
        }
    }

    public function prepareAndExecute($sql, $params)
    {
        // Die SQL-Abfrage vorbereiten
        $stmt = $this->_conn->prepare($sql);

        // Parameter an die vorbereitete Anweisung binden
        $stmt->bind_param(...$params);

        // Die vorbereitete Anweisung ausführen
        $stmt->execute();

        // Das Ergebnis abrufen
        $result = $stmt->get_result();

        // Ressourcen freigeben
        $stmt->close();

        return $result;
    }

    public function __destruct()
    {
        // Verbindung zur Datenbank schließen
        // Null-safe operator
        $this->_conn?->close();
    }
}
