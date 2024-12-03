<h1><?php echo $pagetitle ?></h1>
<?php
echo markdown($item['content']);

if (isUserLoggedIn()):
	// edit link
	$link = '/'.$controller->getAppName().'/admin/edit/'.escUrl($item['id']);
	$linkAttributes = [
		'class' => 'btn btn-info',
	];
	echo '<div>'.createLink($link, '編集する', $linkAttributes, false, false).'</div>';
endif;
