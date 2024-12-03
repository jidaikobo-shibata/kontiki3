<?php
namespace Kontiki3\Core\Controllers\Hard;

use Kontiki3\Core\Controllers\Base\Controller as BaseController;
use Kontiki3\Core\Models\Base\Option as Option; // no option for Hard delete controller
use Kontiki3\Core\Pagination;
use Kontiki3\Core\Response;
use Kontiki3\Core\Input;
use Kontiki3\Core\View;
use Kontiki3\Core\Log;

/**
 * Base Controller class
 */
abstract class Controller extends BaseController
{
	public function __construct()
	{
		View::setHeaderPath($this->getHeaderPath());
		View::setFooterPath($this->getFooterPath());
		parent::__construct();
	}

	protected function getListFilterOptions(): Option
	{
		return (new Option())
			->setSort('created_at', 'DESC');
	}

	protected function getItemFilterOptions(): Option
	{
		return (new Option());
	}

	// Abstract methods to ensure derived classes define header and footer paths
	abstract protected function getHeaderPath(): string;
	abstract protected function getFooterPath(): string;

	/**
	 * Display a single item.
	 *
	 * @param integer $id The id of the item.
	 */
	public function actionItem($id)
	{
		$filter = $this->getItemFilterOptions();
		$item = $this->model->getItemById($id, $filter);

		if (!$item) {
			// Item not found
			Response::send404();
		}

		// Render the item view for logged-in users or public articles
		Response::renderStandardPage(
			$this->getItemViewPath(),
			[
				'controller' => $this,
				'pagetitle' => $this->getListPageTitle(),
				'item' => $item
			],
			$this->getItemPageTitle()
		);
	}

	abstract protected function getItemPageTitle(): string;
	abstract protected function getItemViewPath(): string;

	public function actionList()
	{
		$this->handleList(
			$this->getListViewPath(),
			$this->getListPageTitle(),
			function ($pagination) {
				$filter = $this->getListFilterOptions();
				$filter->setPagination($pagination->getOffset(), $pagination->getLimit());
				return $filter;
			}
		);
	}

	abstract protected function getListPageTitle(): string;
	abstract protected function getListViewPath(): string;
	abstract protected function getListPerPage(): int;

	/**
	 * Handles the listing of items with pagination and optional filters.
	 *
	 * @param string $viewPath The view path to render the page.
	 * @param string $pageTitle The title of the page.
	 * @param callable|null $filterCallback A callback to generate filter options.
	 * @return void
	 */
	protected function handleList(string $viewPath, string $pageTitle, callable $filterCallback = null)
	{
		// Initialize Pagination
		$page = Input::get('page', 1);
		$itemsPerPage = $this->getListPerPage();
		$pagination = new Pagination($page, $itemsPerPage);

		// Generate filter options using callback
		$filter = $filterCallback ? $filterCallback($pagination) : null;

		// Add search term to filter
		$searchTerm = Input::get('s', null);
		if ($searchTerm) {
			$filter->setSearchTerm($searchTerm);
		}

		// Update total items based on filter
		$totalItems = $this->model->getTotalItems($filter);
		$pagination->setTotalItems($totalItems);

		// Apply pagination to filter
		if ($filter) {
			$filter->setPagination($pagination->getOffset(), $pagination->getLimit());
		}

		// Fetch items based on filter
		$items = $this->model->getItems($filter);

		// Render the page
		Response::renderStandardPage(
			$viewPath,
			[
				'controller' => $this,
				'pagetitle' => $pageTitle,
				'items' => $items,
				'pagination' => $pagination
			],
			$pageTitle
		);
	}

}
