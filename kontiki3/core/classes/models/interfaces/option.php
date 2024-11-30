<?php
namespace Kontiki3\Core\Models\Interfaces;

/**
 * Interface
 *
 * This interface serves as a type marker for options passed to the `getItems()` method.
 * It is intentionally left empty, providing a common type across different option classes
 * (e.g., `Models\Base\Option` and `Models\Soft\Option`).
 *
 * By using this interface, we ensure compatibility for the `getItems()` method across
 * multiple classes that implement distinct option handling without enforcing specific methods.
 *
 * @package Kontiki3\Core\Models
 */
interface Option
{
	// This interface is intentionally left empty to allow flexible implementation of
	// option-related methods in classes like `Models\Base\Option` and `Models\Soft\Option`.
}
