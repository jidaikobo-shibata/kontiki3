<?php
namespace Kontiki3\Core\Apps\Soft;

use Kontiki3\Core\Models\Soft\Model as SoftModel;
use Kontiki3\Core\Db;
use Kontiki3\Core\Log;

abstract class Model extends SoftModel
{
	public function __construct()
	{
		parent::__construct();
	}
}
