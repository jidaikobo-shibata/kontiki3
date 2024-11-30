<?php
namespace Kontiki3\Core;

class Session
{
	/**
	 * Start the session with secure settings.
	 *
	 * @return void
	 */
	public static function start()
	{
		if (session_status() === PHP_SESSION_NONE) {
			// Set secure session settings
			ini_set('session.cookie_secure', 1); // Ensure cookies are sent over HTTPS only
			ini_set('session.cookie_httponly', 1); // Prevent JavaScript access to session cookies
			ini_set('session.cookie_lifetime', 0); // Session cookie will be deleted when the browser is closed
			ini_set('session.use_strict_mode', 1); // Prevent accepting uninitialized session IDs

			// Start the session
			session_start();

			// Prevent session fixation attacks by regenerating the session ID
			self::regenerate();
		}
	}

	/**
	 * Periodically regenerates the session ID to improve security.
	 *
	 * This helps prevent session fixation and reduces session hijacking risk.
	 *
	 * @return void
	 */
	public static function regenerate()
	{
		if (session_status() === PHP_SESSION_ACTIVE) {
			// Get last regeneration time; default to 0 if not set
			$last_regenerated = $_SESSION['last_regenerated'] ?? 0;
			$interval = 300; // Regenerate every 5 minutes (300 seconds)

			// Regenerate if enough time has passed
			if (time() - $last_regenerated > $interval) {
				session_regenerate_id(true); // Regenerate and delete old session ID
				$_SESSION['last_regenerated'] = time(); // Update last regeneration time
			}
		}
	}

	/**
	 * Set a value in the session.
	 *
	 * @param string $key The key for the session variable.
	 * @param mixed $value The value to store in the session.
	 * @return void
	 */
	public static function set($key, $value)
	{
		self::start();
		$_SESSION[$key] = $value;
	}

	/**
	 * Get a value from the session.
	 *
	 * @param string $key The key for the session variable.
	 * @return mixed|null The value from the session or null if not set.
	 */
	public static function get($key)
	{
		self::start();
		return $_SESSION[$key] ?? null;
	}

	/**
	 * Get a value from the session and remove it if it exists, otherwise return a default value.
	 *
	 * @param string $key The key for the session variable.
	 * @param mixed $default The default value to return if the key does not exist.
	 * @return mixed The value from the session or the default value.
	 */
	public static function getOnce($key, $default = null)
	{
		self::start();

		if (isset($_SESSION[$key])) {
			$value = $_SESSION[$key];
			unset($_SESSION[$key]);
			return $value;
		}

		return $default;
	}

	/**
	 * Check if a key exists in the session.
	 *
	 * @param string $key The key to check in the session.
	 * @return bool True if the key exists, false otherwise.
	 */
	public static function exists($key)
	{
		self::start();
		return isset($_SESSION[$key]);
	}

	/**
	 * Remove a value from the session.
	 *
	 * @param string $key The key for the session variable.
	 * @return void
	 */
	public static function remove($key)
	{
		self::start();
		unset($_SESSION[$key]);
	}

	/**
	 * Destroy the entire session.
	 *
	 * @return void
	 */
	public static function destroy()
	{
		if (session_status() !== PHP_SESSION_NONE) {
			session_destroy();
		}
	}
}
