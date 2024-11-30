<?php
namespace Kontiki3\Core\Apps\Auth;

use Kontiki3\Core\Session;
use Kontiki3\Core\Input;
use Kontiki3\Core\View;
use Kontiki3\Core\Response;

abstract class Controller
{
	/**
	 * Display the login page.
	 *
	 * @return void
	 */
	public function actionLogin()
	{
		if (Input::hasPost()) {
			self::login();
			return;
		}
		Response::applySecurityHeaders();
		View::render(__DIR__ . '/views/login.php');
	}

	/**
	 * Handle user login.
	 *
	 * @return void
	 */
	private function login()
	{
		// Start the session securely
		Session::start();

		// Get sanitized POST values for 'username' and 'password'
		$username = Input::post('username', FILTER_SANITIZE_STRING);
		$password = Input::post('password', FILTER_SANITIZE_STRING);

		// Load users from the project/configs/users.php file
		$users = KONTIKI3_USERS;

		// Check if the provided username and password match any user
		foreach ($users as $user) {
			if ($user['username'] === $username && password_verify($password, $user['password'])) {
				// Successful login
				Session::set('loggedin', true);
				Session::set('username', $username);
				Session::set('role', 'user'); // Adjust role as needed

				// Regenerate session ID to prevent session fixation attacks
				Session::regenerate();

				// Redirect to a protected page
				Response::redirect('/');
				exit();
			}
		}

		// If no match found, redirect back to login page with an error
		Response::redirect('/login/?error=invalid_credentials');
		exit();
	}

	/**
	 * logout.
	 *
	 * @return void
	 */
	public function actionLogout()
	{
		self::logout();
	}

	/**
	 * Handle user logout.
	 *
	 * @return void
	 */
	private function logout()
	{
		// Start the session if not started already
		Session::start();

		// Destroy the session to log the user out
		Session::destroy();

		// Redirect to the login page
		Response::redirect('/login/');
		exit();
	}
}
