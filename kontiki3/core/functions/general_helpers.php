<?php

/**
 * Get the home URL of the application.
 *
 * @return string The home URL.
 */
if (!function_exists('homeUrl')) {
	function homeUrl() {
		return KONTIKI3_HOMEURL;
	}
}

/**
 * Get the sitetitle.
 *
 * @return string The sitetitle.
 */
if (!function_exists('sitetitle')) {
	function sitetitle() {
		return KONTIKI3_SITETITLE;
	}
}
