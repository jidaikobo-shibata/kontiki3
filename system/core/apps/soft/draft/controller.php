<?php
namespace Kontiki3\Core\Apps\Soft\Draft;

use Kontiki3\Core\Apps\Soft\Controller as SoftController;
use Kontiki3\Core\Apps\Soft\Draft\Option as DraftOption;
use Kontiki3\Core\Response;
use Kontiki3\Core\Log;

/**
 * Soft and Draft Base Controller class.
 */
abstract class Controller extends SoftController
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function getListFilterOptions(): Option
	{
		return (new DraftOption())
			->setDraft(false)
			->setTrashed(false)
			->setSort('created_at', 'DESC');
	}

	protected function getItemFilterOptions(): Option
	{
		return (new DraftOption())
			->setDraft(false)
			->setTrashed(false);
	}

	/**
	 * Display a single information item.
	 *
	 * @param string $slug The slug of the information item.
	 */
	public function actionItemBySlug($slug)
	{
		$item = $this->model->getItemByField('slug', $slug);

		if (!$item) {
			// Item not found
			Response::send404();
		}

		// Check if the item is a draft and the user is not logged in
		if ($item['is_draft'] == 1 && !isUserLoggedIn()) {
			// Display a 404 error for non-logged-in users
			Response::send404();
		}

		// Render the item view for logged-in users or public articles
		Response::renderStandardPage(
			$this->getItemViewPath(),
			[
				'controller' => $this,
				'pagetitle' => $this->getItemPageTitle(),
				'item' => $item
			],
			$this->getItemPageTitle()
		);
	}
}
