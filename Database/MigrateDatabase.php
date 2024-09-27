<?php

require_once __DIR__ . '/../Helpers/DatabaseHelper.php';

class MigrateDatabase
{
	function executeSqlFile($filePath, $dbUser, $dbPassword)
	{
		// Erstellen einer Instanz des DatabaseHelper
		$dbHelper = new DatabaseHelper($dbUser, $dbPassword);

		// SQL-Datei lesen
		// https://www.php.net/manual/de/function.file-get-contents.php
		$sql = file_get_contents($filePath);
		if ($sql === false) {
			die("Fehler beim Lesen der Datei: $filePath\n");
		}

		// SQL-Befehle aufteilen
		// https://www.php.net/manual/de/function.explode.php
		$sqlCommands = explode(";", $sql);

		foreach ($sqlCommands as $command) {
			$command = trim($command);
			if (!empty($command)) {
				try {
					// SQL-Befehl ausführen
					$dbHelper->prepareAndExecute($command, []);
					echo "Ausgeführt: $command\n";
				} catch (PDOException $e) {
					echo "Fehler bei der Ausführung des Befehls: $command\n";
					echo "Fehler: " . $e->getMessage() . "\n";
				}
			}
		}
	}
}
