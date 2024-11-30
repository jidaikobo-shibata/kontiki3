<?php
namespace Kontiki3\Core;

class Maintenance
{
	/**
	 * Backup the SQLite database file.
	 *
	 * If a backup file for today's date already exists, the function will do nothing.
	 * Otherwise, it will create a copy of the database with the current date as a suffix.
	 *
	 * @return void
	 */
	public static function backupDb()
	{
		// Define paths
		$dbPath = KONTIKI3_PROJECT_PATH . '/db/kontiki3.db';
		$dateSuffix = date('Ymd'); // Today's date in YYYYMMDD format
		$backupPath = KONTIKI3_PROJECT_PATH . '/db/kontiki3.sqlite.' . $dateSuffix;

		// Check if today's backup already exists
		if (file_exists($backupPath)) {
			return; // Backup for today already exists, do nothing
		}

		// Perform the backup
		if (!copy($dbPath, $backupPath)) {
			throw new \RuntimeException("Failed to backup the database to: $backupPath");
		}
	}
}
