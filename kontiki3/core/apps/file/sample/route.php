<?php
// route.php

/*
	// sample
	'/core/file/upload/' => [
		'controller' => 'Kontiki3\Core\Apps\File\Controller',
		'method' => 'ajaxHandleFileUpload'
	],
*/

return [

	'/core/file/upload/' => [
		'controller' => 'Kontiki3\Core\Apps\File\Controller',
		'method' => 'ajaxHandleFileUpload'
	],
	'/core/file/update/' => [
		'controller' => 'Kontiki3\Core\Apps\File\Controller',
		'method' => 'ajaxHandleFileUpdate'
	],
	'/core/file/delete/' => [
		'controller' => 'Kontiki3\Core\Apps\File\Controller',
		'method' => 'ajaxHandleFileDelete'
	],
	'/core/file/filelist/' => [
		'controller' => 'Kontiki3\Core\Apps\File\Controller',
		'method' => 'ajaxFilelist'
	],
	'/core/file/get_csrf_token/' => [
		'controller' => 'Kontiki3\Core\Apps\File\Controller',
		'method' => 'ajaxGenerateCsrfToken'
	],
	'/core/file/script/' => [
		'controller' => 'Kontiki3\Core\Apps\File\Controller',
		'method' => 'serveJs'
	],

];
