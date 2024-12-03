<?php
echo '<h1 class="fs-5 text-white bg-dark p-3 rounded mb-4">記事の管理</h1>';
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
	<div class="container-fluid">
		<div class="collapse navbar-collapse show">
			<ul class="navbar-nav">
				<li class="nav-link"><a href="/<?php echo $controller->getAppName() ?>/admin/">記事一覧</a></li>
				<li class="nav-link"><a href="/<?php echo $controller->getAppName() ?>/admin/trashed/">ごみ箱</a></li>
				<li class="nav-link"><a href="/<?php echo $controller->getAppName() ?>/admin/create/">新規作成</a></li>
			</ul>
		</div>
<?php
$formAction = $controller->getAppName().'/admin/';
if (strpos($_SERVER['REQUEST_URI'], '/trashed/') !== false) {
	$formAction = $controller->getAppName().'/admin/trashed/';
}
?>
		<form action="/<?php echo $formAction ?>" method="get" class="d-flex" role="search">
			<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="s" value="<?php echo escHtml(Kontiki3\Core\Input::get('s', '')) ?>">
			<button class="btn btn-outline-success" type="submit">Search</button>
		</form>
	</div>
</nav>

<?php
$html = '';
if (empty($items)):
	$html.= '<p>no items</p>';
else:
	$html.= '<table class="table table-striped table-hover table-responsive">';
	$html.= '<thead class="table-light">';
	$html.= '<tr class="table-dark">';
	$html.= '<th scope="col" class="col-7">記事の名前</th>';
	$html.= '<th scope="col" class="text-center">操作</th>';
	$html.= '<th scope="col" class="text-center">状態</th>';
	$html.= '</tr>';
	$html.= '</thead>';
	foreach ($items as $item):
		$html.= '<tr>';
		$html.= '<th scope="row">'.escHtml($item['title']).'</th>';
		$html.= '<td class="text-center">';
		$html.= '<a href="'.homeUrl().'/information/admin/edit/'.escUrl($item['id']).'" class="btn btn-sm btn-primary me-2">編集する</a>';
		$html.= '<a href="'.homeUrl().'/information/'.escUrl($item['slug']).'" class="btn btn-sm btn-secondary">表示する</a>';
		$html.= '</td>';
		$html.= '<td class="text-center">';
		if (!is_null($item['deleted_at'])):
			$html.= '<em>削除済み</em>';
		elseif ($item['is_draft'] == 1):
			$html.= '下書き（一般非公開）';
		else:
			$html.= '一般公開';
		endif;
		$html.= '</td>';
		$html.= '</tr>';
	endforeach;
	$html.= '</table>';
endif;
echo $html;

// pagination
echo renderPagination($pagination);
