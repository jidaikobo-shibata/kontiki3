<?php
/**
 * Array Helper Functions
 *
 * This file contains a collection of helper functions that replicate the
 * functionalities of the FuelPHP Arr class. Each function is designed to
 * work with nested arrays and provides a convenient way to manipulate,
 * retrieve, and filter array data.
 */

if (!function_exists('arrGet')) {
	/**
	 * Retrieve a value from a nested array using a specified key.
	 *
	 * @param array $array The array to search in.
	 * @param string|array $key The key as a dot-separated string or an array of keys.
	 * @param mixed $default The default value to return if the key is not found.
	 * @return mixed The value of the specified key, or the default value if not found.
	 */
	function arrGet($array, $key, $default = null) {
		if (is_null($key)) {
			return $array;
		}

		// Convert dot notation to an array of keys
		$keys = is_array($key) ? $key : explode('.', $key);

		foreach ($keys as $key) {
			if (is_array($array) && array_key_exists($key, $array)) {
				$array = $array[$key];
			} else {
				return $default;
			}
		}

		return $array;
	}
}

if (!function_exists('arrSet')) {
	/**
	 * Set a value in a nested array using a specified key.
	 *
	 * @param array &$array The array to modify.
	 * @param string|array $key The key as a dot-separated string or an array of keys.
	 * @param mixed $value The value to set at the specified key.
	 * @return void
	 */
	function arrSet(&$array, $key, $value) {
		if (is_null($key)) {
			return;
		}

		// Convert dot notation to an array of keys
		$keys = is_array($key) ? $key : explode('.', $key);

		foreach ($keys as $i => $keyPart) {
			// If we are at the last key, set the value
			if ($i === count($keys) - 1) {
				$array[$keyPart] = $value;
			} else {
				// Ensure the key exists in the array and is an array
				if (!isset($array[$keyPart]) || !is_array($array[$keyPart])) {
					$array[$keyPart] = [];
				}

				// Move to the next level in the array
				$array = &$array[$keyPart];
			}
		}
	}
}

if (!function_exists('arrDelete')) {
	/**
	 * Delete a value from a nested array using a specified key.
	 *
	 * @param array &$array The array to modify.
	 * @param string|array $key The key as a dot-separated string or an array of keys.
	 * @return void
	 */
	function arrDelete(&$array, $key) {
		if (is_null($key)) {
			return;
		}

		// Convert dot notation to an array of keys
		$keys = is_array($key) ? $key : explode('.', $key);

		foreach ($keys as $i => $keyPart) {
			// If we are at the last key, unset the value
			if ($i === count($keys) - 1) {
				unset($array[$keyPart]);
			} else {
				// Move to the next level in the array if it exists, otherwise exit
				if (!isset($array[$keyPart]) || !is_array($array[$keyPart])) {
					return;
				}

				$array = &$array[$keyPart];
			}
		}
	}
}

if (!function_exists('arrPluck')) {
	/**
	 * Pluck a list of values from a nested array by a specified key.
	 *
	 * @param array $array The array to search in.
	 * @param string $key The key to pluck values from.
	 * @param string|null $index The key to use as the index for the returned array.
	 * @return array The array of plucked values.
	 */
	function arrPluck($array, $key, $index = null) {
		$results = [];

		foreach ($array as $item) {
			$value = null;

			// Check if the item is an array or an object
			if (is_array($item) && array_key_exists($key, $item)) {
				$value = $item[$key];
			} elseif (is_object($item) && isset($item->$key)) {
				$value = $item->$key;
			}

			if ($index !== null) {
				$indexValue = is_array($item) && array_key_exists($index, $item) ? $item[$index] : (is_object($item) && isset($item->$index) ? $item->$index : null);
				$results[$indexValue] = $value;
			} else {
				$results[] = $value;
			}
		}

		return $results;
	}
}

if (!function_exists('arrFilter')) {
	/**
	 * Filter the array using a callback function.
	 *
	 * @param array $array The array to filter.
	 * @param callable $callback The callback function to use for filtering.
	 * @return array The filtered array.
	 */
	function arrFilter($array, $callback) {
		$results = [];

		foreach ($array as $key => $value) {
			if (call_user_func($callback, $value, $key)) {
				$results[$key] = $value;
			}
		}

		return $results;
	}
}
