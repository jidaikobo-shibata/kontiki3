<?php
if (!function_exists('formatBytes')) {
	/**
	 * Format a number of bytes into a human-readable string.
	 *
	 * @param int $bytes The number of bytes.
	 * @param int $decimals The number of decimal points to show.
	 * @return string Human-readable formatted string.
	 */
	function formatBytes($bytes, $decimals = 2) {
		$units = ['B', 'KB', 'MB', 'GB', 'TB'];
		$factor = floor((strlen($bytes) - 1) / 3);
		$formatted = $bytes / pow(1024, $factor);

		return round($formatted, $decimals) . ' ' . $units[$factor];
	}
}
