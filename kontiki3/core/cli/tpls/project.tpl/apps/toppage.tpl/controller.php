<?php
namespace Kontiki3\Toppage;

use Kontiki3\Core\View;
use Kontiki3\Core\Response;

/**
 * Handles requests for the topage.
 */
class Controller
{
	public function actionToppage()
	{
		Response::applySecurityHeaders();
		View::incHeader([
				'pagetitle' => "Toppage page."
			]);
		View::render(__DIR__ . '/views/toppage.php');
		View::incFooter();
	}
}
