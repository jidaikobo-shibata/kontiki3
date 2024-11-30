<?php
// route.php

/*
	'/login/' => [
		'controller' => 'Kontiki3\Auth\Controller',
		'method' => 'actionLogin'
	],
*/

return [
	// login
	'/login/' => [
		'controller' => 'Kontiki3\Auth\Controller',
		'method' => 'actionLogin'
	],
	'/logout/' => [
		'controller' => 'Kontiki3\Auth\Controller',
		'method' => 'actionLogout'
	],
];
