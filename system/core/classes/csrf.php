<?php
namespace Kontiki3\Core;

use Kontiki3\Core\Session;
use Kontiki3\Core\Input;

/**
 * Class for managing CSRF tokens using the Session and Input classes.
 */
class Csrf
{
	/**
	 * Generate a CSRF token and store it in the session.
	 *
	 * @param string $formName The name of the form or action.
	 * @return string The generated CSRF token.
	 */
	public static function generateToken($formName)
	{
		$token = bin2hex(random_bytes(32));
		Session::set("csrf_tokens.$formName", $token);
		return $token;
	}

	/**
	 * Validate a submitted CSRF token using the Input class.
	 *
	 * @param string $formName The name of the form or action.
	 * @return bool True if the token is valid, false otherwise.
	 */
	public static function validateToken($formName)
	{
		// Retrieve the token from POST data using the Input class
		$token = Input::post('csrf_token', '');

		$storedToken = Session::get("csrf_tokens.$formName");

		if ($storedToken && hash_equals($storedToken, $token)) {
			Session::remove("csrf_tokens.$formName"); // Token should only be used once
			return true;
		}

		return false;
	}
}
