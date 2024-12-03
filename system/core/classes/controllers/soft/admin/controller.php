<?php
namespace Kontiki3\Core\Controllers\Soft\Admin;

use Kontiki3\Core\Controllers\Hard\Admin\Controller as BaseController;
use Kontiki3\Core\Models\Soft\Option;
use Kontiki3\Core\Response;

/**
 * Soft Controller class
 */
abstract class Controller extends BaseController
{
	protected function getListFilterOptions(): Option
	{
		return (new Option())
			->setTrashed(false) // to collect trash items
			->setSort('created_at', 'DESC');
	}

	public function actionTrashList()
	{
		$this->handleList(
			$this->getListViewPath(),
			$this->getListPageTitle(),
			function ($pagination) {
				// Create a filter for trashed items
				$filter = (new Option())->setTrashed(true);
				return $filter;
			}
		);
	}

	/**
	 * Handle soft deletion of an information item.
	 *
	 * @param int $id The ID of the information item to soft delete.
	 */
	public function actionSoftDelete(int $id)
	{
		if ($this->model->softDelete($id)) {
			Response::redirect("/".$this->getAppName()."/admin/edit/{$id}");
		} else {
			Response::send500('Failed to trash the item.');
		}
	}

	/**
	 * Handle restoring of a soft deleted information item.
	 *
	 * @param int $id The ID of the information item to restore.
	 */
	public function actionRestore(int $id)
	{
		if ($this->model->restore($id)) {
			Response::redirect("/".$this->getAppName()."/admin/edit/{$id}");
		} else {
			Response::send500('Failed to restore the item.');
		}
	}
}
