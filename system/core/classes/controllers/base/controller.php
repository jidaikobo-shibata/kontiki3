<?php
namespace Kontiki3\Core\Controllers\Base;

use Kontiki3\Core\Models\Interfaces\Option as OptionInterface;
use Kontiki3\Core\Response;

/**
 * Base Controller class
 */
abstract class Controller
{
	protected $model;

	public function __construct()
	{
		$this->model = $this->getModelInstance();
	}

	abstract protected function getModelInstance();

	public function getAppName()
	{
		$reflection = new \ReflectionClass($this);
		$namespaceParts = explode('\\', $reflection->getNamespaceName());
		return strtolower($namespaceParts[1]);
	}

	protected function denyIfNotAdmin()
	{
		if (!isUserLoggedIn()) {
			Response::send403();
			exit;
		}
	}

	abstract protected function getListFilterOptions(): OptionInterface;
	abstract protected function getItemFilterOptions(): OptionInterface;
}
