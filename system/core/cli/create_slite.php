<?php
namespace Kontiki3\Core;

require_once dirname(__DIR__, 2) . '/kontiki3.php';

use Kontiki3\Core\Log;

// Define the SQLite database path (constant should already exist in kontiki3.php)
if (!defined('KONTIKI3_SQLITE_DB_PATH')) {
	die("Error: The constant 'KONTIKI3_SQLITE_DB_PATH' is not defined.\n");
}

try {
	// Check if the SQLite file already exists
	if (file_exists(KONTIKI3_SQLITE_DB_PATH)) {
		echo "The SQLite database file already exists: " . KONTIKI3_SQLITE_DB_PATH . "\n";
		exit(0); // Success exit code
	}

	// Create the SQLite database file
	$pdo = new \PDO("sqlite:" . KONTIKI3_SQLITE_DB_PATH);
	$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

	echo "SQLite database file created successfully: " . KONTIKI3_SQLITE_DB_PATH . "\n";

	// Optionally log the creation
	Log::write("SQLite database file created: " . KONTIKI3_SQLITE_DB_PATH);

	exit(0); // Success exit code
} catch (\Exception $e) {
	// Handle exceptions and output the error message
	echo "Error creating SQLite database file: " . $e->getMessage() . "\n";

	// Optionally log the error
	Log::write("Error creating SQLite database file: " . $e->getMessage(), 'ERROR');

	exit(1); // Error exit code
}
