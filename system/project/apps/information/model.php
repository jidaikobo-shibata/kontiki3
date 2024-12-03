<?php
namespace Kontiki3\Information;

use Kontiki3\Core\Apps\Soft\Draft\Model as SoftDraftModel;

/**
 * Model class for handling CRUD operations.
 */
class Model extends SoftDraftModel
{
	protected $table = 'information';
	protected $properties = [
		'title' => [
			'label' => '記事の名前',
			'description' => '',
			'default' => '',
			'validation' => [
				'required' => true
			],
		],
		'content' => [
			'label' => '記事の本文',
			'description' => '',
			'default' => '',
		],
		'slug' => [
			'label' => 'パス名',
			'description' => '',
			'default' => '',
			'validation' => [
				'alnumhyphendot' => true,
				'unique' => true,
				'max' => 255,
				'prohibited' => ['edit', 'create'],
			]
		],
		'is_draft' => [
			'label' => '状態',
			'description' => '',
			'default' => '1',
		],
	];
}
