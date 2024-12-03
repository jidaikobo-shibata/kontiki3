<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

	<title>管理者ログイン - <?php echo sitetitle() ?></title>
</head>
<body class="bg-light">

	<!-- Main content -->
	<main class="container my-5">

		<h1>管理者ログイン - <?php echo sitetitle() ?></h1>
		<?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials'): ?>
			<p style="color: red;">ユーザ名かパスワードが間違っています。</p>
		<?php endif; ?>
		<form action="/login/?action=login" method="post">

		<div class="input-group mb-2">
				<label for="username" class="input-group-text">ユーザ名</label>
				<input type="text" id="username" class="form-control fs-4" name="username" required>
		</div>

		<div class="input-group mb-2">
				<label for="password" class="input-group-text">パスワード</label>
				<input type="password" class="form-control fs-4" id="password" name="password" required>
		</div>

		<div class="mb-2">
			<button class="btn btn-primary" type="submit">ログイン</button>
			<a class="btn btn-secondary" href="<?php echo homeUrl() ?>">トップページへ</a>
		</div>
		</form>
	</body>
	</main>
</html>
