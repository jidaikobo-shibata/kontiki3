<?php
namespace Kontiki3\Core\Apps\Soft\Admin;

use Kontiki3\Core\Controllers\Soft\Admin\Controller as BaseController;

/**
 * Controller class for managing CRUD operations for Information.
 */
abstract class Controller extends BaseController
{
	public function __construct()
	{
		$this->denyIfNotAdmin();
		parent::__construct();
	}

	protected function getHeaderPath(): string
	{
		return KONTIKI3_CORE_PATH . '/apps/common/views/header.php';
	}

	protected function getFooterPath(): string
	{
		return KONTIKI3_CORE_PATH . '/apps/common/views/footer.php';
	}

	protected function getListPageTitle(): string
	{
		return 'implement getListPageTitle()';
	}

	protected function getListViewPath(): string
	{
		return __DIR__ . '/views/list.php';
	}

	protected function getListPerPage(): int
	{
		return 10;
	}

	protected function getItemPageTitle(): string
	{
		return 'implement getItemPageTitle()';
	}

	protected function getItemViewPath(): string
	{
		return __DIR__ . '/views/item.php';
	}

	protected function getCreatePageTitle(): string
	{
		return 'implement getCreatePageTitle()';
	}

	protected function getCreateViewPath(): string
	{
		return __DIR__ . '/views/create.php';
	}

	protected function getEditPageTitle(): string
	{
		return 'implement getEditPageTitle()';
	}

	protected function getEditViewPath(): string
	{
		return __DIR__ . '/views/edit.php';
	}
}
