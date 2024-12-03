<?php
namespace Kontiki3\Core;

require_once dirname(__DIR__, 2) . '/kontiki3.php';

use Kontiki3\Core\Autoloader;
use Kontiki3\Core\Log;

// Ensure the SQLite database path is defined
if (!defined('KONTIKI3_SQLITE_DB_PATH')) {
	die("Error: The constant 'KONTIKI3_SQLITE_DB_PATH' is not defined.\n");
}

// Ensure the database file exists
if (!file_exists(KONTIKI3_SQLITE_DB_PATH)) {
	die("Error: SQLite database file not found. Run the database creation script first.\n");
}

try {
	// Connect to the SQLite database
	$pdo = new \PDO("sqlite:" . KONTIKI3_SQLITE_DB_PATH);
	$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

	// Get all application directories
	$appDirs = Autoloader::getAppDirectories();

	// Process each application's sql directory
	foreach ($appDirs as $appDir) {
		$sqlDir = $appDir . '/sql';

		if (!is_dir($sqlDir)) {
			echo "No 'sql' directory found in app: " . basename($appDir) . "\n";
			continue;
		}

		// Get all .sql files in the sql directory
		$sqlFiles = glob($sqlDir . '/*.sql');

		foreach ($sqlFiles as $sqlFile) {
			// Check if the file has already been executed by checking a suffix
			$executedMarker = $sqlFile . '.executed';
			if (file_exists($executedMarker)) {
				echo "Skipping already executed file: " . basename($sqlFile) . "\n";
				continue;
			}

			// Read and execute the SQL file
			$sqlQuery = file_get_contents($sqlFile);
			$pdo->exec($sqlQuery);

			// Mark the file as executed
			touch($executedMarker);

			// Log and output the execution
			Log::write("Executed SQL file: " . $sqlFile);
			echo "Executed SQL file: " . basename($sqlFile) . "\n";
		}
	}

	echo "All SQL scripts executed successfully.\n";
} catch (\PDOException $e) {
	// Handle errors during SQL execution
	echo "Error executing SQL scripts: " . $e->getMessage() . "\n";
	Log::write("Error executing SQL scripts: " . $e->getMessage(), 'ERROR');
	exit(1);
}

exit(0);
