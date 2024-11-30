<?php
use Kontiki3\Core\View;

echo '<h1 class="fs-5 text-white bg-dark p-3 rounded mb-4">記事の作成</h1>';

// message
echo generateStatusSectionFromArray($messages);

// errors
echo generateAllErrorMessagesHtml($errors, $linkText = '入力欄へ')
?>

<form action="/<?php echo $controller->getAppName() ?>/admin/create/" method="post" enctype="multipart/form-data">
	<?php
	View::render(
		__DIR__.'/inc_form.php',
		[
			'token' => $token,
			'data' => $data,
			'errors' => $errors,
		]
	);
	?>

<!-- button group -->
<div class="d-flex flex-column flex-sm-row justify-content-center align-items-center mb-3 mt-4 gap-3">
	<?php
	// submit button
	$buttonAttributes = [
		'class' => 'btn btn-primary',
		'type' => 'submit'
	];
	echo createButton('<span class="bi bi-check"></span> 作成する', 'submit', $buttonAttributes, false, false);
	?>

	<?php
	// open media manager
	$linkAttributes = [
		'class' => 'btn btn-info',
		'data-bs-toggle' => 'modal',
		'data-bs-target' => '#uploadModal',
	];
	echo createLink('#', 'ファイルの管理を開く', $linkAttributes, false, false);
	?>
</div>
</form>

<?php
// include core app - file manager
includeCoreFileManager();
