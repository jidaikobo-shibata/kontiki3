<?php
namespace Kontiki3\Core\Controllers\Soft;

use Kontiki3\Core\Controllers\Hard\Controller as BaseController;
use Kontiki3\Core\Models\Soft\Option;

/**
 * Soft Controller class
 */
abstract class Controller extends BaseController
{
	protected function getListFilterOptions(): Option
	{
		return (new Option())
			->setTrashed(false) // for control soft delete
			->setSort('created_at', 'DESC');
	}

	protected function getItemFilterOptions(): Option
	{
		return (new Option())
			->setTrashed(false); // for control soft delete
	}
}
