<?php
if (!function_exists('strIncrement')) {
	/**
	 * Increment the trailing number in a string.
	 *
	 * If the string does not end with a number, it will be returned unchanged.
	 *
	 * @param string $string The string to increment.
	 * @return string The incremented string.
	 */
	function strIncrement($string) {
		// Use a regular expression to match a number at the end preceded by an underscore
		if (preg_match('/_(\d+)$/', $string, $matches)) {
			// Increment the number
			$number = (int)$matches[1];
			$incrementedNumber = $number + 1;

			// Replace the old number with the incremented number
			return preg_replace('/_\d+$/', '_' . $incrementedNumber, $string);
		}

		// If there is no number at the end, append "_1"
		return $string . '_1';
	}
}
