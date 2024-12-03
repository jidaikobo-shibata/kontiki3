<?php

return [

	'/%s' => [
		'controller' => 'Kontiki3\Page\Controller',
		'method' => 'actionItemBySlug'
	],

	'/page/admin/' => [
		'controller' => 'Kontiki3\Page\Admin\Controller',
		'method' => 'actionList',
		'menu_order' => 20,
		'menu_label' => 'Administration of Page',
	],
	'/page/admin/trashed/' => [
		'controller' => 'Kontiki3\Page\Admin\Controller',
		'method' => 'actionTrashList'
	],
	'/page/admin/create/' => [
		'controller' => 'Kontiki3\Page\Admin\Controller',
		'method' => 'actionCreate'
	],
	'/page/admin/edit/%d' => [
		'controller' => 'Kontiki3\Page\Admin\Controller',
		'method' => 'actionEdit'
	],
	'/page/admin/edit/%d' => [
		'controller' => 'Kontiki3\Page\Admin\Controller',
		'method' => 'actionEdit'
	],
	'/page/admin/trash/%d' => [
		'controller' => 'Kontiki3\Page\Admin\Controller',
		'method' => 'actionSoftDelete'
	],
	'/page/admin/untrash/%d' => [
		'controller' => 'Kontiki3\Page\Admin\Controller',
		'method' => 'actionRestore'
	],
	'/page/admin/delete/%d' => [
		'controller' => 'Kontiki3\Page\Admin\Controller',
		'method' => 'actionHardDelete'
	],
];
