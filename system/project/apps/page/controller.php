<?php
namespace Kontiki3\Page;

use Kontiki3\Core\Apps\Soft\Draft\Controller as SoftDraftController;
use Kontiki3\Page\Model as PageModel;

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
		return new PageModel();
	}
}
