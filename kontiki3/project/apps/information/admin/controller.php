<?php
namespace Kontiki3\Information\Admin;

use Kontiki3\Core\Apps\Soft\Draft\Admin\Controller as SoftDraftController;
use Kontiki3\Information\Model as InformationModel;

/**
 * Controller class for managing CRUD operations.
 */
class Controller extends SoftDraftController
{
	protected $model;

	public function __construct()
	{
		$this->denyIfNotAdmin();
		$this->tokenname = 'information_admin';
		parent::__construct();
	}

	protected function getModelInstance()
	{
		return new InformationModel();
	}
}
