<?php
namespace Kontiki3\Core\Models\Base;

use Kontiki3\Core\Models\Interfaces\Option as OptionInterface;

/**
 * Option class to handle filtering, sorting, and pagination parameters for data retrieval.
 *
 * This class encapsulates various conditions for querying data, such as pagination,
 * and sorting options. It can be passed to model methods to
 * construct complex queries based on specified parameters.
 */
class Option implements OptionInterface
{
	public ?int $offset = null;
	public ?int $limit = null;
	public ?string $sortField = null;
	public string $sortOrder = 'ASC';
	public ?string $searchTerm = null;

	/**
	 * Set search term for filtering items.
	 *
	 * @param string $term Search term.
	 * @return self
	 */
	public function setSearchTerm(?string $term): self
	{
		$this->searchTerm = $term;
		return $this;
	}

	/**
	 * Apply search term to query.
	 *
	 * @param string &$query SQL query.
	 * @param array &$params Parameters for query.
	 * @return void
	 */
	public function applySearchTerm(string &$query, array &$params): void
	{
		if ($this->searchTerm) {
			$query .= " AND (title LIKE :search OR content LIKE :search)";
			$params[':search'] = '%' . $this->searchTerm . '%';
		}
	}

	/**
	 * Set pagination and sorting options for query.
	 */
	public function applyToQuery(&$query, &$params)
	{
	}

	/**
	 * Apply sorting and pagination clauses to the SQL query.
	 *
	 * This method appends `ORDER BY` and `LIMIT` clauses to the SQL query based on
	 * the provided sorting and pagination parameters.
	 *
	 * @param string &$query The base SQL query to modify.
	 * @param array &$params The parameters array for binding pagination values.
	 *                        If pagination is enabled, `:limit` and `:offset` will be added.
	 * @return void
	 */
	public function applyOrderAndLimit(&$query, &$params)
	{
		// Sorting
		if ($this->sortField) {
			$query .= " ORDER BY {$this->sortField} " . strtoupper($this->sortOrder);
		}

		// Pagination
		if ($this->offset !== null && $this->limit !== null) {
			$query .= " LIMIT :limit OFFSET :offset";
			$params[':limit'] = $this->limit;
			$params[':offset'] = $this->offset;
		}
	}

	/**
	 * Set pagination options for query.
	 *
	 * @param int $offset The starting position for records to retrieve.
	 * @param int $limit The maximum number of records to retrieve.
	 * @return $this
	 */
	public function setPagination(int $offset, int $limit): self
	{
		$this->offset = $offset;
		$this->limit = $limit;
		return $this;
	}

	/**
	 * Set sorting options for query.
	 *
	 * @param string $sortField The field name to sort by.
	 * @param string $sortOrder The sorting order, either 'ASC' or 'DESC' (default 'ASC').
	 * @return $this
	 */
	public function setSort(string $sortField, string $sortOrder = 'ASC'): self
	{
		$this->sortField = $sortField;
		$this->sortOrder = $sortOrder;
		return $this;
	}
}
