<?php
namespace Kontiki3\Core;

class Auth
{
	/**
	 * Check if a user is logged in.
	 *
	 * @return bool True if the user is logged in, false otherwise.
	 */
	public static function isLoggedIn()
	{
		Session::start(); // Ensure the session is started
		return Session::get('loggedin') === true;
	}

	/**
	 * Get the username of the logged-in user.
	 *
	 * @return string|null The username if logged in, null otherwise.
	 */
	public static function getUsername()
	{
		Session::start(); // Ensure the session is started
		return Session::get('username');
	}

	/**
	 * Check if the logged-in user has a specific role.
	 *
	 * @param string $role The role to check (e.g., 'admin', 'user').
	 * @return bool True if the user has the specified role, false otherwise.
	 */
	public static function hasRole($role)
	{
		Session::start(); // Ensure the session is started
		return Session::get('role') === $role;
	}
}
