<?php
namespace Kontiki3\Core\Models\Base;

use Kontiki3\Core\Models\Interfaces\Option as OptionInterface;
use Kontiki3\Core\Models\Base\Option;
use Kontiki3\Core\Db;
use Kontiki3\Core\Input;
use Kontiki3\Core\Validator;
use Kontiki3\Core\Log;

/**
 * Abstract base class with common utility methods for models.
 */
abstract class Model
{
	protected $db;

	public function __construct()
	{
		// Initialize the database connection
		$this->db = Db::getInstance()->getConnection();
	}

	public function __toString()
	{
		$reflection = new \ReflectionClass($this);
		$namespaceParts = explode('\\', $reflection->getNamespaceName());
		return strtolower($namespaceParts[1]);
	}

	/**
	 * Get the properties of the model.
	 *
	 * @return array The properties array defined in the model.
	 */
	public function getProperties()
	{
		return $this->properties ?? [];
	}

	public function getPostData(array $defaults = []): array
	{
		$data = [];
		foreach ($this->properties as $key => $attributes) {
			// POSTからの値を取得、なければデフォルト値または既存データ
			$data[$key] = Input::post($key, $defaults[$key] ?? $attributes['default'] ?? '');
		}
		return $data;
	}

	public function getDefaultData(): array
	{
		$data = [];
		foreach ($this->properties as $key => $attributes) {
			$data[$key] = $attributes['default'] ?? ''; // デフォルト値がなければ空文字
		}
		return $data;
	}

	/**
	 * Get the ID of the last inserted row.
	 *
	 * @return int|string The ID of the last inserted row or an empty string if no row has been inserted.
	 */
	public function getLastInsertId()
	{
		return $this->db->lastInsertId();
	}

	/**
	 * Filter the given data array to include only allowed fields.
	 *
	 * @param array $data The data to filter.
	 * @param array $allowedFields The list of allowed fields.
	 * @return array The filtered data.
	 */
	protected function filterAllowedFields(array $data, array $allowedFields)
	{
		return array_intersect_key($data, array_flip($allowedFields));
	}

	/**
	 * Validate input data based on the model's property rules.
	 *
	 * @param array $data The input data to validate.
	 * @param bool $isEdit True if the validation is for editing an existing record, false for creating a new one.
	 * @param int|null $id The ID of the record to exclude during unique checks (used in edit mode).
	 * @return array|bool An array of validation errors if validation fails, or true if validation passes.
	 */
	public function validateData(array $data, bool $isEdit, int $id = null)
	{
		$validator = new Validator();
		if ($isEdit) {
			$validator->setMode('edit', $id);
		}

		$isValid = $validator->validate($data, $this);

		if (!$isValid) {
			return $validator->getErrors();
		}

		return true;
	}

	/**
	 * Check if a value is unique in the database for a specific field, optionally excluding a specific record by ID.
	 *
	 * @param string $field The field name to check for uniqueness.
	 * @param mixed $value The value to check for uniqueness.
	 * @param int|null $excludeId The ID of the record to exclude from the check (used when editing an existing record).
	 * @return bool True if the value is unique, false if it is not.
	 * @throws \InvalidArgumentException If the field is not valid for the current table.
	 */
	public function isUnique(string $field, $value, int $excludeId = null)
	{
		// Validate the field against allowed columns in the current table
		if (!in_array($field, array_keys($this->properties), true)) {
			throw new \InvalidArgumentException("Invalid field: $field");
		}

		// Prepare the SQL query
		$sql = "SELECT COUNT(*) FROM {$this->table} WHERE $field = :value";
		if ($excludeId !== null) {
			$sql .= " AND id != :excludeId";
		}

		$db = Db::getInstance()->getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':value', $value, \PDO::PARAM_STR);
		if ($excludeId !== null) {
			$stmt->bindValue(':excludeId', $excludeId, \PDO::PARAM_INT);
		}
		$stmt->execute();

		// Return true if the count is 0, indicating the value is unique
		$count = $stmt->fetchColumn();
		return $count == 0;
	}

	/**
	 * Retrieve the total count of items in the table, optionally filtered by conditions.
	 *
	 * @param Option|null $option The option options to apply.
	 * @return int The total number of items matching the option.
	 */
	public function getTotalItems(Option $option = null): int
	{
		// Base query
		$query = "SELECT COUNT(*) FROM {$this->table} WHERE 1 = 1";
		$params = [];

		// Apply option conditions if provided
		if ($option) {
			$option->applyToQuery($query, $params);
			$option->applySearchTerm($query, $params);
		}

		// Prepare and execute the query
		$stmt = $this->db->prepare($query);

		// Bind parameters
		foreach ($params as $key => $value) {
			$stmt->bindValue($key, $value);
		}

		$stmt->execute();

		// Fetch and return the count
		return (int) $stmt->fetchColumn();
	}

	/**
	 * Retrieve items based on the given filter or default options.
	 *
	 * @param Option|null $option The filter containing pagination, sort, and condition options (optional).
	 * @return array The list of items based on the filter.
	 */
	public function getItems($option = null): array
	{
		// Verify that $option implements OptionInterface, if provided
		if ($option !== null && !($option instanceof OptionInterface)) {
			throw new \InvalidArgumentException("The provided option must implement OptionInterface.");
		}

		// Use a default Option instance if none is provided
		$option = $option ?? new Option();

		$query = "SELECT * FROM {$this->table} WHERE 1=1";
		$params = [];

		// Apply WHERE conditions
		$option->applyToQuery($query, $params);

		// Apply search term
		$option->applySearchTerm($query, $params);

		// Apply sorting and pagination at the end
		$option->applyOrderAndLimit($query, $params);

		$stmt = $this->db->prepare($query);
		foreach ($params as $param => $value) {
			$stmt->bindValue($param, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
		}
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Retrieve a single record by its ID.
	 *
	 * @param int $id The ID of the record.
	 * @param Option|null $option The filter.
	 * @return array|null The record or null if not found.
	 */
	public function getItemById(int $id, $option = null)
	{
		return $this->getItemByField('id', $id, $option);
	}

	/**
	 * Retrieve a single record by a specific field.
	 *
	 * @param string $field The field to search by.
	 * @param mixed $value The value to search for.
	 * @param Option|null $option The filter option.
	 * @return array|null The record if found and unique, otherwise null.
	 * @throws \RuntimeException If multiple records are found for the given field and value.
	 */
	public function getItemByField(string $field, $value, $option = null)
	{
		// Verify that $option implements OptionInterface, if provided
		if ($option !== null && !($option instanceof OptionInterface)) {
			throw new \InvalidArgumentException("The provided option must implement OptionInterface.");
		}

		// Base query to retrieve the item by the specified field
		$query = "SELECT * FROM {$this->table} WHERE {$field} = :value";
		$params = [':value' => $value];

		// Apply additional filters if Option object is provided
		if ($option) {
			$option->applyToQuery($query, $params);
		}

		// Prepare and execute the query
		$stmt = $this->db->prepare($query);

		// Bind parameters
		foreach ($params as $key => $value) {
			$stmt->bindValue($key, $value, \PDO::PARAM_STR);
		}

		$stmt->execute();

		// Fetch all matching rows
		$results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		// Check the result count
		if (count($results) > 1) {
			throw new \RuntimeException("Multiple records found for {$field} = {$value}.");
		} elseif (count($results) === 0) {
			return null; // No record found
		}

		// Return the single matching record
		return $results[0];
	}

	/**
	 * Create a new record with specified data.
	 *
	 * @param array $data The data to insert as an associative array.
	 * @return bool True on success, false on failure.
	 */
	public function createItem(array $data)
	{
		// Filter only the fields that are allowed to be created
		$allowedFields = array_keys($this->properties);
		$insertData = $this->filterAllowedFields($data, $allowedFields);

		$columns = implode(", ", array_keys($insertData));
		$placeholders = ":" . implode(", :", array_keys($insertData));

		$stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");

		foreach ($insertData as $key => &$value) {
			$stmt->bindParam(":$key", $value);
		}

		return $stmt->execute();
	}

	/**
	 * Update an existing record with specified data.
	 *
	 * @param int $id The ID of the record to update.
	 * @param array $data The data to update as an associative array.
	 * @return bool True on success, false on failure.
	 */
	public function updateItem(int $id, array $data)
	{
		// Filter only the fields that are allowed to be updated
		$allowedFields = array_keys($this->properties);
		$updateData = $this->filterAllowedFields($data, $allowedFields);

		$setClauses = [];
		foreach ($updateData as $key => $value) {
			$setClauses[] = "$key = :$key";
		}
		$setString = implode(", ", $setClauses);

		$stmt = $this->db->prepare("UPDATE {$this->table} SET $setString, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);

		foreach ($updateData as $key => &$value) {
			$stmt->bindParam(":$key", $value);
		}
		return $stmt->execute();
	}

	/**
	 * Hard delete a record by its ID.
	 *
	 * @param int $id The ID of the record.
	 * @return bool True on success, false on failure.
	 */
	public function hardDelete($id)
	{
		$stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		return $stmt->execute();
	}
}
