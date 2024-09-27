<?php
require_once __DIR__ . '/MigrateDatabase.php';
require_once __DIR__ . '/../.env.php';

$dbUser = getenv('DB_USER'); 
$dbPassword = getenv('PW_DB');

$migrateDatabase = new MigrateDatabase();

// Get the current directory
$directory = __DIR__;

// Find all .sql files in the directory
// https://www.php.net/manual/en/function.glob.php
$sqlFiles = glob($directory . '/*.sql');

if (empty($sqlFiles)) {
    echo "No SQL files found in the directory.\n";
} else {
    foreach ($sqlFiles as $file) {
        echo "Processing: $file\n";
        $migrateDatabase->executeSqlFile($file, $dbUser, $dbPassword);
    }
}
?>
