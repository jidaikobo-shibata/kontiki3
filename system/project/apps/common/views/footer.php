</main><!-- /Main content -->

<footer class="text-center py-4 mt-auto bg-white border-top">
<?php
	// logout
	$html = '';
	$html.= '<p>';
	$html.= isUserLoggedIn() ? '<a href="/logout/">logout</a>' : '<a href="/login/">login</a>';
	$html.= '</p>';
	echo $html;
?>
</footer>
</body>
</html>
