<?php
namespace Kontiki3\Core;

/**
 * Class Autoloader
 *
 * A simple autoloader class for loading classes within the Kontiki3 namespace.
 */
class Autoloader
{
	/**
	 * Registers the autoload function with PHP's SPL autoload stack.
	 *
	 * @return void
	 */
	public static function register()
	{
		spl_autoload_register([__CLASS__, 'autoload']);
	}

	/**
	 * Autoloads a class file based on its namespace and class name.
	 *
	 * @param string $className The fully qualified name of the class to load.
	 * @return void
	 */
	private static function autoload($className)
	{
		// Ensure the class belongs to the Kontiki3 namespace
		if (strpos($className, 'Kontiki3\\') === 0) {
			// Replace namespace separators with directory separators and convert to lowercase
			$classPath = strtolower(str_replace('\\', DIRECTORY_SEPARATOR, substr($className, strlen('Kontiki3') + 1)));

			// core
			if (strpos($className, 'Kontiki3\Core\Apps') !== false) {
				$classPath = str_replace('core/', '/', $classPath);
				$filePath = strtolower(KONTIKI3_CORE_PATH . '/' . $classPath . '.php');
			// core apps
			} else if (strpos($className, 'Kontiki3\Core') !== false) {
				$classPath = str_replace('core/', '/classes/', $classPath);
				$filePath = strtolower(KONTIKI3_CORE_PATH . $classPath . '.php');
			// project apps
			} else {
				$filePath = strtolower(KONTIKI3_PROJECT_PATH . '/apps/' . $classPath . '.php');
			}

			// Check if the file exists and include it
			if (file_exists($filePath)) {
				require_once $filePath;
			} else {
				// Log an error message if the file is not found (optional)
				// Log::write('Controller or method not found: '.$className, 'error');
				Log::debug('Controller or method not found: '.$className);
				Response::send404();
			}
		}
	}

	/**
	 * Get a list of all application directories under the apps path.
	 *
	 * @return array An array of application directory paths.
	 */
	public static function getAppDirectories(): array
	{
		static $cachedAppDirs = null;

		if ($cachedAppDirs === null) {
			$appsPath = KONTIKI3_PROJECT_PATH . '/apps';
			if (!is_dir($appsPath)) {
				$cachedAppDirs = [];
			} else {
				$cachedAppDirs = array_filter(
					glob($appsPath . '/*', GLOB_ONLYDIR),
					function ($dir) {
						return is_dir($dir);
					}
				);
			}
		}

		return $cachedAppDirs;
	}

	/**
	 * Load base helper functions
	 */
	public static function loadBaseHelperFunctions()
	{
		$directories = [KONTIKI3_PROJECT_PATH . '/functions', KONTIKI3_CORE_PATH . '/functions'];
		foreach ($directories as $directory) {
			self::loadFunctions($directory);
		}
	}

	/**
	 * Load PHP
	 *
	 * @param string $directory The directory to load functions from
	 */
	public static function loadPhps($directory)
	{
		if (is_dir($directory)) {
			foreach (glob($directory . '/*.php') as $file) {
				require_once $file;
			}
		}
	}

	/**
	 * Load global helper functions
	 *
	 * @param string $directory The directory to load functions from
	 */
	public static function loadFunctions($directory)
	{
		self::loadPhps($directory);
	}

	/**
	 * Load config functions
	 *
	 */
	public static function loadConfigs()
	{
		self::loadPhps(KONTIKI3_ROOT_PATH . '/project/configs/');
	}

	/**
	 * Load functions from each app's functions directory
	 */
	public static function loadAppFunctions()
	{
		// Load functions for each app
		foreach (self::getAppDirectories() as $path) {
			$functionDir = $path . '/functions';
			self::loadFunctions($functionDir);
		}
	}
}
