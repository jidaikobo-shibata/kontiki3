<?php
namespace Kontiki3\{{appName_cap}};

use Kontiki3\Core\Apps\File\Model as FileBaseModel;

class Model extends FileBaseModel
{
	protected $table = '{{appName_lower}}';
	protected $properties = [
		'path' => [],
		'description' => [
      'label' => '説明',
			'validation' => [
				'max' => 255
			]
		]
	];
}
