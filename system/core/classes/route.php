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
     * Extract dynamic parameters from a URI based on a pattern.
     *
     * @param string $pattern The pattern to match (e.g., '/%s').
     * @param string $uri The request URI.
     * @return array|null Extracted parameters, or null if no match.
     */
    private static function extractParameters($pattern, $uri)
    {
        $regexPattern = str_replace(['%d', '%s'], ['(\d+)', '([^/]+)'], $pattern);
        $regexPattern = '#^' . $regexPattern . '$#';

        if (preg_match($regexPattern, $uri, $matches)) {
            array_shift($matches); // Remove the full match
            return $matches;
        }
        return null;
    }

    /**
     * Dispatch the request to the appropriate controller and method.
     *
     * @param string $requestUri The request URI to route.
     * @return void
     */
    public static function dispatch($requestUri)
    {
        $defaultRoute = null;

        // Iterate over each app in the configuration
        foreach (Autoloader::getAppDirectories() as $path) {
            $routeFile = $path . '/route.php';

            // Load the app's route.php file if it exists
            if (file_exists($routeFile)) {
                $routes = include $routeFile;

                // Process the routes for this app
                foreach ($routes as $pattern => $route) {
                    // Store the default route for later processing
                    if ($pattern === '/%s') {
                        $defaultRoute = $route;
                        continue;
                    }

                    // Try to extract parameters from the current URI
                    $matches = self::extractParameters($pattern, $requestUri);
                    if ($matches !== null) {
                        self::invokeController($route['controller'], $route['method'], $matches);
                        return;
                    }
                }
            }
        }

        // Handle the default route if no other routes matched
        if ($defaultRoute !== null) {
            $matches = self::extractParameters('/%s', $requestUri);
            if ($matches !== null) {
                self::invokeController($defaultRoute['controller'], $defaultRoute['method'], $matches);
                return;
            }
        }

        // If no matching route is found, return a 404 response
        Log::write('No matching route found: ' . $requestUri, 'error');
        Response::send404();
    }

    /**
     * Invoke the specified controller and method with the provided parameters.
     *
     * @param string $controllerClass Fully qualified class name of the controller.
     * @param string $method Method name to invoke on the controller.
     * @param array $parameters Parameters to pass to the method.
     * @return void
     */
    private static function invokeController($controllerClass, $method, $parameters)
    {
        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            $controller = new $controllerClass();
            call_user_func_array([$controller, $method], $parameters);
        } else {
            Log::write('Controller or method not found: ' . $controllerClass . '::' . $method, 'error');
            Response::send404();
        }
    }
}
