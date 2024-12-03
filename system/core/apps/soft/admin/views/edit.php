<?php
use Kontiki3\Core\View;

echo '<h1 class="fs-5 text-white bg-dark p-3 rounded mb-4">記事の編集</h1>';

// message
echo generateStatusSectionFromArray($messages);

// errors
echo generateAllErrorMessagesHtml($errors, $linkText = '入力欄へ')
?>

<form action="/<?php echo $controller->getAppName() ?>/admin/edit/<?php echo intval($id) ?>" method="post" enctype="multipart/form-data">

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
	<div class="btn-group me-3">
	<?php
	// contextual link
	$baseUrl = '/'.$controller->getAppName().'/admin/';
	$viewUrl = '/'.$controller->getAppName().'/' . escUrl($item['slug']);
	$trashUrl = $baseUrl . 'trash/' . intval($id);
	$untrashUrl = $baseUrl . 'untrash/' . intval($id);
	$deleteUrl = $baseUrl . 'delete/' . intval($id);

	$buttonAttributes = [
		'class' => 'btn btn-primary',
		'type' => 'submit'
	];
	$viewAttributes = [
		'class' => 'btn btn-secondary'
	];
	$trashAttributes = [
		'class' => 'btn btn-warning',
		'data-confirm' => 'non-critical'
	];
	$untrashAttributes = [
		'class' => 'btn btn-warning',
		'data-confirm' => 'non-critical'
	];
	$deleteAttributes = [
		'class' => 'btn btn-danger',
		'data-confirm' => 'critical'
	];
	if (is_null($item['deleted_at'])) :
		echo createButton('更新する <span class="bi bi-check"></span>', 'submit', $buttonAttributes, false, false);
		echo createLink($viewUrl, '表示する <span class="bi bi-eye"></span>', $viewAttributes, false, false);
		echo createLink($trashUrl, '削除する', $trashAttributes);
	else :
		echo createLink($untrashUrl, '復活させる', $untrashAttributes);
		echo createLink($deleteUrl, '完全に削除する', $deleteAttributes);
	endif;
	?>
	</div>

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
