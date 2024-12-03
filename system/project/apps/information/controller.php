<?php
namespace Kontiki3\Information;

use Kontiki3\Core\Apps\Soft\Draft\Controller as SoftDraftController;
use Kontiki3\Information\Model as InformationModel;

/**
 * Controller class for managing CRUD operations for Information.
 */
class Controller extends SoftDraftController
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function getModelInstance()
	{
		return new InformationModel();
	}
}
