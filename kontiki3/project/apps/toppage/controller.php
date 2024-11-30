<?php
namespace Kontiki3\Toppage;

use Kontiki3\Core\Response;

/**
 * Handles requests for the topage.
 */
class Controller
{
	public function actionToppage()
	{
		$pageTitle = "Toppage page.";
		$viewPath = __DIR__ . '/views/toppage.php';
		$params = [
			'controller' => $this,
			'pagetitle' => $pageTitle,
		];
		Response::renderStandardPage($viewPath, $params, $pageTitle);
	}
}
