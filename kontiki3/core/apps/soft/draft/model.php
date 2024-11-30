<?php
namespace Kontiki3\Core\Apps\Soft\Draft;

use Kontiki3\Core\Models\Soft\Model as SoftModel;
use Kontiki3\Core\Apps\Soft\Draft\Option as DraftOption;
use Kontiki3\Core\Log;

abstract class Model extends SoftModel
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Retrieve items based on the given filter or default options.
	 *
	 * @param DraftOption|null $option The filters.
	 * @return array The list of items based on the filter.
	 */
	public function getItems($option = null): array
	{
		$option = $option ?? new DraftOption();
		return parent::getItems($option);
	}

	/**
	 * Retrieve a single record by its ID.
	 *
	 * @param int $id The ID of the record.
	 * @param Option|null $option The filters.
	 * @return array|null The record or null if not found.
	 */
	public function getItemById(int $id, $option = null)
	{
		$option = $option ?? new DraftOption();
		return parent::getItemById($id, $option);
	}
}
