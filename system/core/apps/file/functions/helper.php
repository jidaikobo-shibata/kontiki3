<?php
use Kontiki3\Core\View;

if (!function_exists('includeCoreFileManager')) {
	/**
	 * Includes the core file manager view.
	 *
	 * This function is responsible for including the core file manager view from
	 * the `apps/file/views` directory of the core application. It utilizes the
	 * `View::render` method to render the `inc_file_manager.php` view, which is
	 * typically used to display the file manager interface in the application.
	 *
	 * @return void
	 */
	function includeCoreFileManager() {
		View::render(KONTIKI3_CORE_PATH.'/apps/file/views/inc_file_manager.php');
	}
}

if (!function_exists('pathToUrl')) {
	function pathToUrl($path) {
		return str_replace(KONTIKI3_PUBLIC_PATH, KONTIKI3_HOMEURL, $path);
	}
}
