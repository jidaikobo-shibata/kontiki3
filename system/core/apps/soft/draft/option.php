<?
namespace Kontiki3\Core\Apps\Soft\Draft;

use Kontiki3\Core\Models\Soft\Option as SoftOption;

class Option extends SoftOption
{
	public ?bool $isDraft = null;

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

		// Apply draft status filter
		if ($this->isDraft !== null) {
			$query .= " AND is_draft = :isDraft";
			$params[':isDraft'] = (int)$this->isDraft;
		}
	}

	/**
	 * Set the draft status for filtering.
	 *
	 * @param bool|null $isDraft True for drafts, false for published, null for both.
	 * @return $this
	 */
	public function setDraft(?bool $isDraft): self
	{
		$this->isDraft = $isDraft;
		return $this;
	}

	/**
	 * Include both draft and published items.
	 *
	 * This method resets the draft filter to include both published and draft items.
	 *
	 * @return $this
	 */
	public function includeDrafts(): self
	{
		$this->isDraft = null;
		return $this;
	}
}
