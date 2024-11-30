<?php
namespace Kontiki3\Core\Models\Soft;

use Kontiki3\Core\Models\Base\Model as BaseModel;
use Kontiki3\Core\Db;
use Kontiki3\Core\Log;

/**
 * Base model class for common database operations with soft delete support.
 */
abstract class Model extends BaseModel
{
	protected $db;
	protected $table;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Soft delete a record by its ID.
	 *
	 * @param int $id The ID of the record.
	 * @return bool True on success, false on failure.
	 */
	public function softDelete($id)
	{
		$stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = CURRENT_TIMESTAMP WHERE id = :id");
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		return $stmt->execute();
	}

	/**
	 * Restore a soft deleted record by its ID.
	 *
	 * @param int $id The ID of the record.
	 * @return bool True on success, false on failure.
	 */
	public function restore($id)
	{
		$stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NULL WHERE id = :id");
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		return $stmt->execute();
	}

	/**
	 * Update an existing record with specified data.
	 *
	 * @param int $id The ID of the record to update.
	 * @param array $data The data to update as an associative array.
	 * @return bool True on success, false on failure.
	 */
	public function updateItem($id, $data)
	{
		$setClauses = [];
		foreach ($data as $key => $value) {
			$setClauses[] = "$key = :$key";
		}
		$setString = implode(", ", $setClauses);

		$stmt = $this->db->prepare("UPDATE {$this->table} SET $setString, updated_at = CURRENT_TIMESTAMP WHERE id = :id AND deleted_at IS NULL");
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);

		foreach ($data as $key => &$value) {
			$stmt->bindParam(":$key", $value);
		}
		return $stmt->execute();
	}
}
