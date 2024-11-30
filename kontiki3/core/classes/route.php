<?php
namespace Kontiki3\Core;

use Kontiki3\Core\Response;
use Kontiki3\Core\Log;

/**
 * Route class
 *
 * Handles routing logic for the Kontiki3 CMS.
 */
class Route
{
	/**
	 * Dispatch the request to the appropriate controller and method.
	 *
	 * @param string $requestUri The request URI to route.
	 * @return void
	 */
	public static function dispatch($requestUri)
	{
		// Iterate over each app in the configuration
		foreach (Autoloader::getAppDirectories() as $path) {
			$routeFile = $path . '/route.php';

			// Load the app's route.php file if it exists
			if (file_exists($routeFile)) {
				$routes = include $routeFile;

				// Process the routes for this app
				foreach ($routes as $pattern => $route) {
					// Convert placeholders to regular expressions
					$regexPattern = str_replace(['%d', '%s'], ['(\d+)', '([^/]+)'], $pattern);
					$regexPattern = '#^' . $regexPattern . '$#';

					// Check if the request URI matches the pattern
					if (preg_match($regexPattern, $requestUri, $matches)) {
						array_shift($matches); // Remove the full match (matches[0])

						// Create the controller instance with the namespace of the app
						$controllerClass = $route['controller'];
						$method = $route['method'];

						if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
							$controller = new $controllerClass();
							call_user_func_array([$controller, $method], $matches);
							return;
						} else {
							// Handle the case where the controller or method is not found
							Log::write('Controller or method not found: ' . $controllerClass . '::' . $method, 'error');
							Response::send404();
						}
					}
				}
			}
		}

		// If no matching route is found, return a 404 response
		Log::write('No matching route found. ', 'error');
		Response::send404();
	}
}
