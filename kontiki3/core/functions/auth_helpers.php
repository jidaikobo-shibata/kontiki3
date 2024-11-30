<?php
use Kontiki3\Core\Session;

/**
 * Check if a user is logged in using the Session class.
 *
 * @return bool True if the user is logged in, false otherwise.
 */
if (!function_exists('isUserLoggedIn')) {
	function isUserLoggedIn() {
		return Session::get('loggedin') !== null;
	}
}
