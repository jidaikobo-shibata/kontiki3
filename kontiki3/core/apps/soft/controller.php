<?php
namespace Kontiki3\Core\Apps\Soft;

use Kontiki3\Core\Controllers\Soft\Controller as SoftController;
use Kontiki3\Core\Models\Soft\Option;

/**
 * Soft Base Controller class.
 */
abstract class Controller extends SoftController
{
	protected function getListPerPage(): int
	{
		return 10;
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

	protected function getItemPageTitle(): string
	{
		return 'implement getItemPageTitle()';
	}

	protected function getItemViewPath(): string
	{
		return __DIR__ . '/views/item.php';
	}
}
