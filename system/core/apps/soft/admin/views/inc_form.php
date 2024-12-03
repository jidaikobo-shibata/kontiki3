<!-- Title section -->
<div class="mb-3 row">
	<?php
	$inputId = 'title';
	$attributes = [
		'class' => 'form-control fs-5' . (isset($errors[$inputId]) ? ' is-invalid' : ''),
		'id' => $inputId,
		'required' => 'required'
	];
	$attributes = generateErrorAttributes($errors, 'title', $inputId, $attributes);
	echo createLabel('記事の名前', 'title', ['class' => 'col-sm-2 col-form-label']);
	echo '<div class="col-sm-10">';
	echo createInput('title', 'text', escHtml($data['title']), $attributes);
	echo '</div>';
	?>
</div>

<!-- Content section -->
<div class="mb-3 row">
	<?php
	$inputId = 'content';
	$attributes = [
		'class' => 'form-control fs-5' . (isset($errors[$inputId]) ? ' is-invalid' : ''),
		'id' => $inputId,
		'rows' => '5',
		'aria-describedby' => 'contentHelp',
	];
	$attributes = generateErrorAttributes($errors, 'content', $inputId, $attributes);
	echo createLabel('記事の本文', 'content', ['class' => 'col-sm-2 col-form-label']);
	echo '<div class="col-sm-10">';
	echo createTextarea('content', $data['content'], $attributes);
	echo '<small id="contentHelp" class="form-text text-muted">MarkDown（マークダウン）記法で入力してください（<a href="https://michelf.ca/projects/php-markdown/extra/" target="markdown" class="text-muted">MarkDown記法の使い方を新しいタブで開く</a>）。本文に画像やファイルを置きたい場合は、このページの下の方にある「ファイルの管理を開く」でアップロードなどの管理ができます。</small>';
	echo '</div>';
	?>
</div>

<!-- Slug section -->
<div class="mb-3 row">
	<?php
	$inputId = 'slug';
	$attributes = [
		'class' => 'form-control fs-5' . (isset($errors[$inputId]) ? ' is-invalid' : ''),
		'id' => $inputId,
		'required' => 'required',
		'aria-describedby' => 'slugHelp',
	];
	$attributes = generateErrorAttributes($errors, 'slug', $inputId, $attributes);
	echo createLabel('パス名', 'slug', ['class' => 'col-sm-2 col-form-label']);
	echo '<div class="col-sm-10">';
	echo createInput('slug', 'text', escHtml($data['slug']), $attributes);
	echo '<small id="slugHelp" class="form-text text-muted">パス名は、URLの一部として使われます。<code>'.homeUrl().'/information/</code>の後に続きます。記事を識別する短い名前（例: \'<code>my-article-title</code>\'）を半角英数字とハイフンで入力してください。</small>';
	echo '</div>';
	?>
</div>

<!-- Draft section -->
<div class="mb-3 row">
	<?php
	$inputId = 'is_draft';
	$attributes = [
		'class' => 'form-control fs-5',
		'id' => $inputId,
		'aria-describedby' => 'isDraftHelp',
	];
	$options = [
		['value' => '0', 'label' => '一般公開'],
		['value' => '1', 'label' => '下書き（一般非公開）']
	];
	echo createLabel('状態', $inputId, ['class' => 'col-sm-2 col-form-label']);
	echo '<div class="col-sm-10">';
	echo createSelect('is_draft', array_column($options, 'label', 'value'), $data['is_draft'], $attributes);
	echo '<small id="isDraftHelp" class="form-text text-muted">一般公開前に表示確認（プレビュー）したい場合は、「下書き（一般非公開）」で保存してください。ログインしたユーザだけ見られるようになります。</small>';
	echo '</div>';
	?>
</div>

<?php
// CSRF
echo createInput('csrf_token', 'hidden', escHtml($token), ['type' => 'hidden']);
?>
