<?php
require_once __DIR__ . '/MigrateDatabase.php';

$dbUser = "root"; 
$dbPassword = "q1w2e3r4";

$migrateDatabase = new MigrateDatabase();

// Aktuelles Verzeichnis abfragen
$directory = __DIR__;

// Alle .sql-Dateien im Verzeichnis finden
$sqlFiles = glob($directory . '/*.sql');

if (empty($sqlFiles)) {
    echo "Keine SQL-Dateien im Verzeichnis gefunden.\n";
} else {
    foreach ($sqlFiles as $file) {
        echo "Verarbeite: $file\n";
        $migrateDatabase->executeSqlFile($file, $dbUser, $dbPassword);
    }
}
?>
