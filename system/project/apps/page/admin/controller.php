<?php
namespace Kontiki3\Page\Admin;

use Kontiki3\Core\Apps\Soft\Draft\Admin\Controller as SoftDraftController;
use Kontiki3\Page\Model as PageModel;

/**
 * Controller class for managing CRUD operations.
 */
class Controller extends SoftDraftController
{
	protected $model;

	public function __construct()
	{
		$this->denyIfNotAdmin();
		$this->tokenname = 'page_admin';
		parent::__construct();
	}

	protected function getModelInstance()
	{
		return new PageModel();
	}
}
