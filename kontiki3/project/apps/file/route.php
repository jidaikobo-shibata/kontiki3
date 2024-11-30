<?php

return [

	'/file/upload/' => [
		'controller' => 'Kontiki3\File\Controller',
		'method' => 'ajaxHandleFileUpload'
	],
	'/file/update/' => [
		'controller' => 'Kontiki3\File\Controller',
		'method' => 'ajaxHandleFileUpdate'
	],
	'/file/delete/' => [
		'controller' => 'Kontiki3\File\Controller',
		'method' => 'ajaxHandleFileDelete'
	],
	'/file/filelist/' => [
		'controller' => 'Kontiki3\File\Controller',
		'method' => 'ajaxFilelist'
	],
	'/file/get_csrf_token/' => [
		'controller' => 'Kontiki3\File\Controller',
		'method' => 'ajaxGenerateCsrfToken'
	],
	'/file/script/file_manager/' => [
		'controller' => 'Kontiki3\File\Controller',
		'method' => 'serveJs'
	],
	'/file/script/file_manager_instance/' => [
		'controller' => 'Kontiki3\File\Controller',
		'method' => 'serveJsFileManagerInstance'
	],

];
