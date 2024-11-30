<?php
use Kontiki3\Core\Autoloader;

if (isUserLoggedIn()):

$menuRoutes = [];

foreach (Autoloader::getAppDirectories() as $path) {
	$routeFile = $path . '/route.php';

	// Load the app's route.php file if it exists
	if (file_exists($routeFile)) {
		$routes = include $routeFile;

		// Filter routes with 'menu_order'
		foreach ($routes as $route => $details) {
			if (isset($details['menu_order'])) {
				$menuRoutes[] = [
					'route' => $route,
					'menu_label' => $details['menu_label'],
					'menu_order' => $details['menu_order']
				];
			}
		}
	}
}

// Sort the extracted routes by 'menu_order'
usort($menuRoutes, function ($a, $b) {
	return $a['menu_order'] <=> $b['menu_order'];
});

?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top py-0 small bg-warning bg-gradient">
	<div class="container-fluid">
		<a class="navbar-brand fs-6" href="#">管理メニュー</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="adminNavbar">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">管理項目</a>
					<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
						<?php
						$html = '';
						foreach ($menuRoutes as $values):
							$html.= '<li class="dropdown-item"><a href="'.$values['route'].'">'.$values['menu_label'].'</a></li>';
						endforeach;
						echo $html;
						?>
					</ul>
				</li>
			</ul>
			<ul class="navbar-nav mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link py-0" href="/logout/">ログアウト</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
<?php endif; ?>
