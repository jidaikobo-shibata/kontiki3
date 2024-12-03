<?php
namespace Kontiki3\Page;

use Kontiki3\Core\Apps\Soft\Draft\Model as SoftDraftModel;

/**
 * Model class for handling CRUD operations.
 */
class Model extends SoftDraftModel
{
	protected $table = 'page';
	protected $properties = [
		'title' => [
			'label' => 'ページの名前',
			'description' => '',
			'default' => '',
			'validation' => [
				'required' => true
			],
		],
		'content' => [
			'label' => 'ページの本文',
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
		'parent_id' => [
			'label' => '親ページ',
			'description' => '',
		],
		'is_draft' => [
			'label' => '状態',
			'description' => '',
			'default' => '1',
		],
	];
}
