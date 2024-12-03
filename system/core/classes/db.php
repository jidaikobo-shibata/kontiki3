<?php
namespace Kontiki3\Core;

/**
 * Database class
 *
 * Handles SQLite database connection and operations using the Singleton pattern.
 */
class Db
{
	/**
	 * @var Db|null The single instance of the Db class.
	 */
	private static $instance = null;

	/**
	 * @var \PDO The PDO instance for the database connection.
	 */
	private $pdo;

	/**
	 * Private constructor to prevent direct instantiation.
	 *
	 * @throws \PDOException If the connection fails.
	 */
	private function __construct()
	{
		try {
			// Get the path to the SQLite database from the config file
			$dbPath = KONTIKI3_SQLITE_DB_PATH;

			// Create a new PDO instance for SQLite connection
			$this->pdo = new \PDO("sqlite:" . $dbPath);
			$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (\PDOException $e) {
			// Log the error message and rethrow the exception
			Log::write("Database connection failed: " . $e->getMessage(), 'ERROR');
			throw $e;
		}
	}

	/**
	 * Get the single instance of the Db class.
	 *
	 * @return Db The single instance of the Db class.
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new Db();
		}
		return self::$instance;
	}

	/**
	 * Get the PDO instance.
	 *
	 * @return \PDO The PDO instance for database operations.
	 */
	public function getConnection()
	{
		return $this->pdo;
	}

	/**
	 * Prevent the instance from being cloned.
	 */
	private function __clone() {}

	/**
	 * Prevent unserializing of the instance.
	 */
	private function __wakeup() {}
}
