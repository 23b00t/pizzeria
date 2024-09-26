<?php

// INFO: Resourcen:
// https://www.w3schools.com/php/php_mysql_connect.asp
// https://www.php.net/manual/en/ref.pdo-mysql.php
// https://www.ibm.com/docs/en/dscp/10.1.0?topic=ess-preparing-executing-sql-statements
// https://www.ibm.com/docs/en/dscp/10.1.0?topic=rqrs-fetching-rows-columns-from-result-sets
// https://www.php.net/manual/de/pdo.constants.php#pdo.constants.fetch-assoc

class DatabaseHelper
{
    private $_conn;

    public function __construct($dbuser, $dbpassword)
    {
        $servername = "127.0.0.1";
        $dbname = "pizzeria";
        $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";

        try {
            // Verbindung herstellen mit PDO
            $this->_conn = new PDO($dsn, $dbuser, $dbpassword);
            // PDO Fehler-Modus auf Exception setzen
            $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Verbindung fehlgeschlagen: " . $e->getMessage() . "\n");
        }
    }

    public function prepareAndExecute($sql, $params)
    {
        // Die SQL-Abfrage vorbereiten
        $stmt = $this->_conn->prepare($sql);

        // Die vorbereitete Anweisung ausführen
        $stmt->execute($params);

        // Das Ergebnis abrufen
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function __destruct()
    {
        // Verbindung zur Datenbank schließen
        $this->_conn = null;
    }
}
