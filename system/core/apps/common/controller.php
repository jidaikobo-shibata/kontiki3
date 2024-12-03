<?php
namespace Kontiki3\Core\Apps\Common;

use Kontiki3\Core\Response;

/**
 * Common Controller class.
 */
abstract class Controller
{
	/**
	 * Serve the requested JavaScript file.
	 *
	 * @return void
	 */
	public function serveJs()
	{
		// isUserLoggedIn
		if (!isUserLoggedIn()) {
			die("Invalid Access.");
		}
		Response::sendJsFile(KONTIKI3_CORE_PATH . '/apps/common/js/confirm_dialog.js');
	}
}
