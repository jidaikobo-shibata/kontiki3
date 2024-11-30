<?php
namespace Kontiki3\Core;

class Input
{
	/**
	 * Get a value from GET request, handling both single and array inputs.
	 *
	 * @param string $name The input name.
	 * @param mixed $default return when post data is not exist.
	 * @param int $filter The filter to apply.
	 * @param array|null $options Optional filter options.
	 * @return mixed The filtered value, or false if the variable does not exist.
	 */
	public static function get($name, $default = '', $filter = FILTER_SANITIZE_STRING, $options = null)
	{
		$value = filter_input(INPUT_GET, $name, FILTER_DEFAULT, $options);
		$value = $value === NULL || $value === false ? $default : $value;
		return self::sanitizeValue($value, $filter, $options);
	}

	/**
	 * Get a value from GET request as an array.
	 *
	 * @param string $name The input name.
	 * @param array $default The default value to return when the get data does not exist.
	 * @param int $filter The filter to apply.
	 * @param array|null $options Optional filter options.
	 * @return array The filtered value or the default array if the variable does not exist.
	 */
	public static function getArr($name, $default = [], $filter = FILTER_DEFAULT, $options = null)
	{
		$value = filter_input_array(INPUT_GET, [$name => ['flags' => FILTER_REQUIRE_ARRAY]]);
		$value = $value === NULL || $value === false ? $default : self::sanitizeValue($value, $filter, $options);
		return is_array($value[$name]) ? $value[$name] : $default;
	}

	/**
	 * Get a value from POST request, handling both single and array inputs.
	 *
	 * @param string $name The input name.
	 * @param mixed $default return when post data is not exist.
	 * @param int $filter The filter to apply.
	 * @param array|null $options Optional filter options.
	 * @return mixed The filtered value, or false if the variable does not exist.
	 */
	public static function post($name, $default = '', $filter = FILTER_DEFAULT, $options = null)
	{
		$value = filter_input(INPUT_POST, $name, FILTER_DEFAULT, $options);
		$value = $value === NULL || $value === false ? $default : $value;
		return self::sanitizeValue($value, $filter, $options);
	}

	/**
	 * Get a value from POST request as an array.
	 *
	 * @param string $name The input name.
	 * @param array $default The default value to return when the post data does not exist.
	 * @param int $filter The filter to apply.
	 * @param array|null $options Optional filter options.
	 * @return array The filtered value or the default array if the variable does not exist.
	 */
	public static function postArr($name, $default = [], $filter = FILTER_DEFAULT, $options = null)
	{
		$value = filter_input_array(INPUT_POST, [$name => ['flags' => FILTER_REQUIRE_ARRAY]]);
		$value = $value === NULL || $value === false ? $default : self::sanitizeValue($value, $filter, $options);
		return is_array($value[$name]) ? $value[$name] : $default;
	}

	/**
	 * Check if there is any POST data.
	 *
	 * @return bool True if POST data exists, false otherwise.
	 */
	public static function hasPost()
	{
		return !empty($_POST);
	}

	/**
	 * Check if the request method is POST.
	 *
	 * @return bool True if the request method is POST, false otherwise.
	 */
	public static function isPostRequest()
	{
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}

	/**
	 * Sanitize a value or an array of values recursively.
	 *
	 * @param mixed $value The value to sanitize.
	 * @param int $filter The filter to apply.
	 * @param array|null $options Optional filter options.
	 * @return mixed The sanitized value or array.
	 */
	private static function sanitizeValue($value, $filter, $options)
	{
		if (is_array($value)) {
			return array_map(function($item) use ($filter, $options) {
				return self::sanitizeValue($item, $filter, $options);
			}, $value);
		} else {
			return filter_var($value, $filter, $options);
		}
	}

	/**
	 * Get a file from FILES request.
	 *
	 * @param string $name The input name.
	 * @return array|null The file data or null if the file does not exist.
	 */
	public static function files($name)
	{
		return isset($_FILES[$name]) && !empty($_FILES[$name]) ? $_FILES[$name] : null;
	}
}
