<?php
namespace Kontiki3\Core;

use Kontiki3\Core\View;

/**
 * Response class for handling HTTP responses in a unified way.
 */
class Response
{
	/**
	 * Send a 200 OK response.
	 *
	 * @param string $message The response message.
	 * @param string $contentType The content type of the response.
	 * @return void
	 */
	public static function send200($message = '', $contentType = 'text/plain')
	{
		header('HTTP/1.0 200 OK');
		header("Content-Type: $contentType");
		echo $message;
		exit;
	}

	/**
	 * Send a 403 Forbidden response.
	 *
	 * @param string $message The response message.
	 * @return void
	 */
	public static function send403($message = 'Access denied.')
	{
		header('HTTP/1.0 403 Forbidden');
		echo $message;
		exit;
	}

	/**
	 * Send a 404 Not Found response.
	 *
	 * @param string $message The response message.
	 * @return void
	 */
	public static function send404($message = 'Item not found.')
	{
		header('HTTP/1.0 404 Not Found');
		echo $message;
		exit;
	}

	/**
	 * Send a 500 Internal Server Error response.
	 *
	 * @param string $message The response message.
	 * @return void
	 */
	public static function send500($message = 'Internal server error.')
	{
		header('HTTP/1.0 500 Internal Server Error');
		echo $message;
		exit;
	}

	/**
	 * Redirect to a specified URL.
	 *
	 * @param string $url The URL to redirect to.
	 * @param int $statusCode The HTTP status code for the redirect. 301 is Moved Permanently.
	 * @return void
	 */
	public static function redirect($url, $statusCode = 302)
	{
		header("Location: $url", true, $statusCode);
		exit;
	}

	/**
	 * Send a JSON response.
	 *
	 * @param array $data The data to send as a JSON response.
	 * @param int $statusCode The HTTP status code.
	 * @return void
	 */
	public static function sendJson(array $data, $statusCode = 200)
	{
		self::applySecurityHeaders();
		header('Content-Type: application/json');
		http_response_code($statusCode);
		echo json_encode($data);
		exit;
	}

	/**
	 * Send a JS response.
	 *
	 * @param string $data The data to send as a JavaScript response.
	 * @param int $statusCode The HTTP status code.
	 * @param int $cacheDuration Cache duration in seconds (default: 1 hour).
	 * @return void
	 */
	public static function sendJsFile($filePath, $statusCode = 200, $cacheDuration = 3600)
	{
		self::applySecurityHeaders();
		header('Content-Type: application/javascript');
		header("Cache-Control: max-age={$cacheDuration}");
		http_response_code($statusCode);
		readfile($filePath);
		exit;
	}

	/**
	 * Set a custom HTTP header.
	 *
	 * @param string $header The header string.
	 * @return void
	 */
	public static function setHeader($header)
	{
		header($header);
	}

	public static function renderStandardPage($viewPath, $data = [], $pageTitle = '')
	{
		self::applySecurityHeaders();
		View::incHeader(['pagetitle' => $pageTitle]);
		View::render($viewPath, $data);
		View::incFooter();
		exit;
	}

	/**
	 * Apply common security headers to enhance the security of the application.
	 *
	 * This method sets various HTTP headers to mitigate common web vulnerabilities,
	 * such as XSS, clickjacking, and content sniffing. These headers provide a basic
	 * layer of protection and help enforce secure practices in browsers.
	 *
	 * @return void
	 */
	public static function applySecurityHeaders()
	{
		// Content Security Policy (CSP) to prevent XSS and data injection attacks
		// Allows content only from the same origin; adjust as needed
		header("Content-Security-Policy: ".
		 "default-src 'self'; ".
		 "script-src 'self' https://cdn.jsdelivr.net https://code.jquery.com; ".
		 "style-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ".
		 "font-src 'self' https://cdnjs.cloudflare.com; ".
		 "img-src 'self' data:; ".
		 "connect-src 'self'; ".
		 "frame-src 'self';");

		// Strict Transport Security (HSTS) to enforce HTTPS usage
		// Ensures the client always connects using HTTPS for the specified duration
		header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

		// X-Content-Type-Options to prevent MIME type sniffing
		// Forces browsers to adhere to the content type declared by the server
		header("X-Content-Type-Options: nosniff");

		// Referrer-Policy to control the referrer information sent with requests
		// This policy helps limit the amount of referrer data shared with third-party sites
		header("Referrer-Policy: no-referrer-when-downgrade");

		// X-XSS-Protection to enable the XSS filter in older browsers
		// Stops pages from loading when XSS attacks are detected
		header("X-XSS-Protection: 1; mode=block");

		// Permissions-Policy (formerly Feature-Policy) to control API and feature access
		// Restricts the usage of browser features such as geolocation and camera
		header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

		// X-Frame-Options to prevent clickjacking by restricting iframe embedding
		// 'SAMEORIGIN' allows only the same site to embed the page in an iframe
		header("X-Frame-Options: SAMEORIGIN");

		// Cross-Origin Resource Sharing (CORS) to control resource sharing between origins
		// Adjust the allowed origin as needed; can be set to '*' for public APIs
		header("Access-Control-Allow-Origin: ".KONTIKI3_HOMEURL);
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

		// End the method without exiting, allowing further processing if needed
	}
}
