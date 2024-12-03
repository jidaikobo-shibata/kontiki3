<?php

return [

	'/information/' => [
		'controller' => 'Kontiki3\Information\Controller',
		'method' => 'actionList'
	],
	'/information/%s' => [
		'controller' => 'Kontiki3\Information\Controller',
		'method' => 'actionItemBySlug'
	],

	'/information/admin/' => [
		'controller' => 'Kontiki3\Information\Admin\Controller',
		'method' => 'actionList',
		'menu_order' => 20,
		'menu_label' => 'Administration of information',
	],
	'/information/admin/trashed/' => [
		'controller' => 'Kontiki3\Information\Admin\Controller',
		'method' => 'actionTrashList'
	],
	'/information/admin/create/' => [
		'controller' => 'Kontiki3\Information\Admin\Controller',
		'method' => 'actionCreate'
	],
	'/information/admin/edit/%d' => [
		'controller' => 'Kontiki3\Information\Admin\Controller',
		'method' => 'actionEdit'
	],
	'/information/admin/edit/%d' => [
		'controller' => 'Kontiki3\Information\Admin\Controller',
		'method' => 'actionEdit'
	],
	'/information/admin/trash/%d' => [
		'controller' => 'Kontiki3\Information\Admin\Controller',
		'method' => 'actionSoftDelete'
	],
	'/information/admin/untrash/%d' => [
		'controller' => 'Kontiki3\Information\Admin\Controller',
		'method' => 'actionRestore'
	],
	'/information/admin/delete/%d' => [
		'controller' => 'Kontiki3\Information\Admin\Controller',
		'method' => 'actionHardDelete'
	],
];
