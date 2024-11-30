<?php
namespace Kontiki3\Core\Models\Soft;

use Kontiki3\Core\Models\Base\Option as BaseOption;

/**
 * Option class to handle filtering, sorting, and pagination parameters for data retrieval.
 *
 * This class encapsulates various conditions for querying data, such as draft status,
 * trash status, pagination, and sorting options. It can be passed to model methods to
 * construct complex queries based on specified parameters.
 */
class Option extends BaseOption
{
	public ?bool $isDraft = null;
	public ?bool $isTrashed = null;

	/**
	 * Apply filters to the query based on the set options.
	 *
	 * @param string &$query The base SQL query to modify.
	 * @param array &$params The parameters to bind to the query.
	 * @return void
	 */
	public function applyToQuery(&$query, &$params)
	{
		// Call the parent method to apply base filters
		parent::applyToQuery($query, $params);

		// Apply trashed status filter
		if ($this->isTrashed !== null) {
			$query .= $this->isTrashed ? " AND deleted_at IS NOT NULL" : " AND deleted_at IS NULL";
		}
	}

	/**
	 * Set the trash (soft-deleted) status filter.
	 *
	 * @param bool $isTrashed True for trashed items, false for non-trashed items.
	 * @return $this
	 */
	public function setTrashed(bool $isTrashed): self
	{
		$this->isTrashed = $isTrashed;
		return $this;
	}
}
