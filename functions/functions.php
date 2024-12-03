<?php
/**
 * Escape HTML special characters for safe output in views.
 *
 * @param string $string The input string to be escaped.
 * @return string The escaped string.
 */
if (!function_exists('escHtml')) {
	function escHtml($string) {
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}
}

/**
 * Perform an HTTP request using cURL.
 *
 * @param string $url The API endpoint URL.
 * @param string $method HTTP method (GET, POST, PUT, DELETE, etc.).
 * @param array $data Optional. Data to send with the request (for POST, PUT, etc.).
 * @param array $headers Optional. Additional headers to include in the request.
 * @return array The response, including status code and body.
 * @throws Exception If the cURL request fails.
 */
function kontiki3HttpRequest($url, $method = 'GET', $data = [], $headers = [])
{
    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method)); // Set the HTTP method
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects if necessary
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set a timeout for the request

    // Add headers
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    // Add data for POST or PUT requests
    if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH']) && !empty($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Assume JSON payload
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Reapply headers with Content-Type
    }

    // Execute the request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $errorMessage = curl_error($ch);
        curl_close($ch);
        throw new Exception('cURL Error: ' . $errorMessage);
    }

    // Close the cURL session
    curl_close($ch);

    // Return the response and status code as an array
    return [
        'status_code' => $httpCode,
        'body' => json_decode($response, true) // Decode JSON response
    ];
}
