<?php

return [

	'/{{appName_lower}}/upload/' => [
		'controller' => 'Kontiki3\{{appName_cap}}\Controller',
		'method' => 'ajaxHandleFileUpload'
	],
	'/{{appName_lower}}/update/' => [
		'controller' => 'Kontiki3\{{appName_cap}}\Controller',
		'method' => 'ajaxHandleFileUpdate'
	],
	'/{{appName_lower}}/delete/' => [
		'controller' => 'Kontiki3\{{appName_cap}}\Controller',
		'method' => 'ajaxHandleFileDelete'
	],
	'/{{appName_lower}}/filelist/' => [
		'controller' => 'Kontiki3\{{appName_cap}}\Controller',
		'method' => 'ajaxFilelist'
	],
	'/{{appName_lower}}/get_csrf_token/' => [
		'controller' => 'Kontiki3\{{appName_cap}}\Controller',
		'method' => 'ajaxGenerateCsrfToken'
	],
	'/{{appName_lower}}/script/file_manager/' => [
		'controller' => 'Kontiki3\{{appName_cap}}\Controller',
		'method' => 'serveJs'
	],
	'/{{appName_lower}}/script/file_manager_instance/' => [
		'controller' => 'Kontiki3\{{appName_cap}}\Controller',
		'method' => 'serveJsFileManagerInstance'
	],

];
