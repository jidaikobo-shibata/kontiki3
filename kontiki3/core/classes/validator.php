<?php
namespace Kontiki3\Core;

/**
 * Validator class for validating input data based on defined rules.
 */
class Validator
{
	protected $errors = [];
	protected $mode = 'create';
	protected $id = null;

	/**
	 * Set the validation mode and ID for edit operations.
	 *
	 * @param string $mode The validation mode ('create' or 'edit').
	 * @param int|null $id The ID to exclude during unique checks (used in 'edit' mode).
	 * @return void
	 */
	public function setMode(string $mode, int $id = null)
	{
		$this->mode = $mode;
		$this->id = $id;
	}

	/**
	 * Validate the input data against the specified rules defined in the model.
	 *
	 * @param array $data The input data to validate.
	 * @param \Kontiki3\Core\Models\Base $model The model instance to obtain validation rules and perform unique checks.
	 * @return bool True if validation passes, false otherwise.
	 */
	public function validate(array $data, $model)
	{
		$this->errors = []; // Reset errors for each validation call
		$rules = $model->getProperties(); // Get properties from the model

		foreach ($rules as $field => $fieldRules) {
			$value = $data[$field] ?? '';
			$label = $fieldRules['label'] ?? $field;

			// Required check
			if (!empty($fieldRules['validation']['required']) && empty($value)) {
				$this->errors[$field][] = sprintf("「%s」が必要です。", $label);
			}

			// Numeric check
			if (!empty($fieldRules['validation']['numeric']) && !is_numeric($value)) {
				$this->errors[$field][] = sprintf("「%s」は数字だけ使えます。", $label);
			}

			// Alphanumeric with hyphen check
			if (!empty($fieldRules['validation']['alnumhyphen']) && !preg_match('/^[a-z0-9-]+$/i', $value)) {
				$this->errors[$field][] = sprintf("「%s」は半角英数字及びハイフンだけ使えます。", $label);
			}

			// Alphanumeric with hyphen and dot check
			if (!empty($fieldRules['validation']['alnumhyphendot']) && !preg_match('/^[a-z0-9\.-]+$/i', $value)) {
				$this->errors[$field][] = sprintf("「%s」は半角英数字、ハイフン及びドットだけ使えます。", $label);
			}

			// Max length check
			if (isset($fieldRules['validation']['max']) && strlen($value) > $fieldRules['validation']['max']) {
				$this->errors[$field][] = sprintf("「%s」は%d文字より少なくしてください。", $label, intval($fieldRules['validation']['max']));
			}

			// Min length check
			if (isset($fieldRules['validation']['min']) && strlen($value) < $fieldRules['validation']['min']) {
				$this->errors[$field][] = sprintf("「%s」は%d文字より多くしてださい。", $label, intval($fieldRules['validation']['min']));
			}

			// Prohibited value check
			if (!empty($fieldRules['validation']['prohibited']) && in_array($value, $fieldRules['validation']['prohibited'], true)) {
				$this->errors[$field][] = sprintf("「%s」に次の値は使えません: %s", $label, join(", ", $fieldRules['validation']['prohibited']));
			}

			// Unique check
			if (!empty($fieldRules['validation']['unique'])) {
				$excludeId = ($this->mode === 'edit') ? $this->id : null;
				if (!$model->isUnique($field, $value, $excludeId)) {
					$this->errors[$field][] = sprintf("「%s」はすでに使われています。", $label);
				}
			}
		}

		return empty($this->errors);
	}

	/**
	 * Get validation errors.
	 *
	 * @return array The array of validation errors.
	 */
	public function getErrors()
	{
		return $this->errors;
	}
}
