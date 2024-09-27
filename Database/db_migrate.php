<?php
require_once __DIR__ . '/MigrateDatabase.php';
include_once __DIR__ . '/../.env.php';

$dbUser = getenv('DB_USER'); 
$dbPassword = getenv('PW_DB');

$migrateDatabase = new MigrateDatabase();

// Aktuelles Verzeichnis abfragen
$directory = __DIR__;

// Alle .sql-Dateien im Verzeichnis finden
// https://www.php.net/manual/de/function.glob.php
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
