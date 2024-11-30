<?php
namespace Kontiki3\Core;

/**
 * Kontiki3
 * author: shibata@jidaikobo.com
 */

// set constant
define('KONTIKI3_ROOT_PATH', __DIR__);
define('KONTIKI3_CORE_PATH', __DIR__.'/core');
define('KONTIKI3_PROJECT_PATH', KONTIKI3_ROOT_PATH.'/project');

// autoload
require_once KONTIKI3_CORE_PATH . '/classes/autoload.php';
Autoloader::register();

// Register the custom handlers
set_error_handler([Log::class, 'errorHandler']);
set_exception_handler([Log::class, 'exceptionHandler']);

// load config
Autoloader::loadConfigs();

// load functions
Autoloader::loadBaseHelperFunctions();
Autoloader::loadAppFunctions();

// database
Maintenance::backupDb();

// Routing (only for HTTP requests)
if (PHP_SAPI !== 'cli') {
	Route::dispatch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}
