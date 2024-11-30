<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- jQuery CDN -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

	<!-- Bootstrap Icon -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

	<!-- Bootstrap Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

	<!-- Scripts -->
	<script src="/assets/js/script.js"></script>
<?php if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false): ?>
	<script src="/common/script/confirm_dialog/"></script>
	<script src="/file/script/file_manager/"></script>
	<script src="/file/script/file_manager_instance/"></script>
<?php endif; ?>

	<!-- stylesheet -->
	<link rel="stylesheet" href="/assets/css/layout.css">

	<title><?php echo escHtml($pagetitle) ?></title>
</head>

<body class="bg-light<?php echo isUserLoggedIn() ? ' mt-4' : '' ; ?>">

<?php incAdminBar(); ?>

<!-- nav -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
<div class="container">
<h2 class="visually-hidden">グローバルナビゲーション</h2>
<ul class="navbar-nav ms-auto">
	<li class="nav-item"><a class="nav-link" href="/">トップページ</a></li>
	<li class="nav-item"><a class="nav-link" href="/information/">インフォメーション</a></li>
</ul>
</div>
</nav>

<!-- Main content -->
<main class="container my-4">
