<?php
namespace Kontiki3\File;

use Kontiki3\Core\Apps\File\Model as FileBaseModel;

class Model extends FileBaseModel
{
	protected $table = 'file';
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
