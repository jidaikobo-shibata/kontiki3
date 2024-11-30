<?php
if (!function_exists('uriCreate')) {
	/**
	 * Create a URI from a specified path and optional query parameters.
	 *
	 * @param string $uri The URI path to create.
	 * @param array $params Optional associative array of query parameters.
	 * @return string The generated URI.
	 */
	function uriCreate($uri, $params = []) {
		// Ensure the URI path starts with a forward slash
		$fullUri = '/' . ltrim($uri, '/');

		// If there are query parameters, append them as a query string
		if (!empty($params)) {
			$queryString = http_build_query($params);
			$fullUri .= '?' . $queryString;
		}

		return $fullUri; // Return the generated URI
	}
}

if (!function_exists('uriCurrent')) {
	/**
	 * Get the current full URI including the scheme and host.
	 *
	 * @return string The current full URI.
	 */
	function uriCurrent() {
		// Get the scheme (http or https)
		$scheme = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';

		// Get the host
		$host = $_SERVER['HTTP_HOST'];

		// Get the request URI
		$requestUri = $_SERVER['REQUEST_URI'];

		// Combine to form the full URI
		return $scheme . '://' . $host . $requestUri;
	}
}

if (!function_exists('buildQueryString')) {
	/**
	 * Build a query string from an associative array of parameters.
	 *
	 * @param array $params The associative array of parameters.
	 * @return string The URL-encoded query string.
	 */
	function buildQueryString($params) {
		// Build the query string using http_build_query
		return http_build_query($params);
	}
}
