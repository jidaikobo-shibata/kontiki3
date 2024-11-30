<?php

/**
 * Escape HTML special characters for safe output in views.
 *
 * @param string $string The input string to be escaped.
 * @return string The escaped string.
 */
if (!function_exists('escHtml')) {
	function escHtml($string) {
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}
}

/**
 * Escape a string for safe use within HTML attributes.
 *
 * @param string $string The input string to be escaped.
 * @return string The escaped string.
 */
if (!function_exists('escAttribute')) {
	function escAttribute($string) {
		return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	}
}

/**
 * Escape a URL to ensure it is safe for output.
 *
 * @param string $url The input URL to be sanitized.
 * @return string The sanitized URL.
 */
if (!function_exists('escUrl')) {
	function escUrl($url) {
		return filter_var($url, FILTER_SANITIZE_URL);
	}
}

/**
 * Escape JavaScript data for inline use in views.
 *
 * @param string $string The input string to be escaped for JavaScript context.
 * @return string The escaped string suitable for inline JavaScript.
 */
if (!function_exists('escJs')) {
	function escJs($string) {
		return json_encode($string, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
	}
}

/**
 * Generate HTML for displaying validation errors for a specific field.
 *
 * @param array $errors An associative array of validation errors, where the key is the field name and the value is an array of error messages.
 * @param string $field The name of the field for which to display errors.
 * @param bool $return If true, returns the HTML as a string; if false, echoes the HTML directly.
 * @param bool $wrapWithUl If true, wraps the error messages in a <ul> element; if false, outputs error messages without <ul> wrapper.
 * @return string|null The HTML string if $return is true, otherwise null.
 */
if (!function_exists('generateErrorsHtml')) {
	function generateErrorsHtml($errors, $field, $return = false, $wrapWithUl = true) {
		if (!isset($errors[$field])) {
			return $return ? '' : null;
		}

		$html = '';

		if ($wrapWithUl) {
			$html .= '<ul class="text-danger small list-unstyled mt-1">';
		}

		foreach ($errors[$field] as $error) {
			$html .= '<li>';
			$html .= escHtml($error);
			$html .= '</li>';
		}

		if ($wrapWithUl) {
			$html .= '</ul>';
		}

		if ($return) {
			return $html;
		} else {
			echo $html;
		}
	}

	/**
	 * Render pagination links based on the provided Pagination instance.
	 *
	 * @param Kontiki3\Core\Pagination $pagination The Pagination instance with the current pagination state.
	 * @param string $baseUrl The base URL to use for the pagination links.
	 * @return string The generated pagination HTML.
	 */
if (!function_exists('renderPagination')) {
	function renderPagination(Kontiki3\Core\Pagination $pagination, string $baseUrl = '?page=', $isAjax = false): string
	{
		$pageLinks = $pagination->getPageLinks();
		$ajaxClass = $isAjax ? ' page-link-ajax' : '';

		if (count($pageLinks) === 1) return '';

		$html = '<nav aria-label="Page navigation">';
		$html .= '<ul class="pagination">';

		// previous link
		if ($pagination->hasPreviousPage()) {
			$previousPage = ($pagination->getCurrentPage() - 1);
			$html .= '<li class="page-item">';
			$html .= '<a class="page-link'.$ajaxClass.'" href="' . $baseUrl . $previousPage . '" aria-label="Previous" data-page="'.$previousPage.'">';
			$html .= '<span aria-hidden="true">&laquo;</span>';
			$html .= '</a></li>';
		} else {
			$html .= '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
		}

		// each page links
		foreach ($pageLinks as $link) {
			$eachPage = $link['page'];
			$activeClass = $link['isCurrent'] ? ' active' : '';
			$ariaCurrent = $link['isCurrent'] ? ' aria-current="page"' : '';
			$html .= '<li class="page-item' . $activeClass . '">';
			$html .= '<a class="page-link'.$ajaxClass.'" href="' . $baseUrl . $eachPage . '"' . $ariaCurrent . ' data-page="'.$eachPage.'">' . $link['page'] . '</a>';
			$html .= '</li>';
		}

		// next link
		if ($pagination->hasNextPage()) {
			$nextPage = ($pagination->getCurrentPage() + 1);
			$html .= '<li class="page-item">';
			$html .= '<a class="page-link'.$ajaxClass.'" href="' . $baseUrl . $nextPage . '" aria-label="Next" data-page="'.$nextPage.'">';
			$html .= '<span aria-hidden="true">&raquo;</span>';
			$html .= '</a></li>';
		} else {
			$html .= '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
		}

		$html .= '</ul>';
		$html .= '</nav>';

		return $html;
	}
}

}

if (!function_exists('renderImageOrLink')) {
	/**
	 * Render an image or a link based on the provided URL.
	 *
	 * @param string $url The input URL, either an image URL or a standard URL.
	 * @param string|null $desc description text.
	 * @return string The generated HTML.
	 */
	function renderImageOrLink(string $url, string $desc): string
	{
		// Check if the URL is an image URL (basic check based on file extension)
		$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
		$pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));

		if (isset($pathInfo['extension']) && in_array(strtolower($pathInfo['extension']), $imageExtensions)) {
			// Return an <img> tag for images
			$descText = htmlspecialchars($desc, ENT_QUOTES, 'UTF-8');
			$imgSrc = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
			return "<img src=\"{$imgSrc}\" alt=\"{$descText}を拡大表示する\" class=\"clickable-image img-thumbnail\" tabindex=\"0\">";
		}

		// Otherwise, return an <a> tag for links
		$linkHref = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

		$extension = isset($pathInfo['extension']) ? strtolower($pathInfo['extension']) : null;

		switch ($extension) {
			case 'pdf':
				$class = 'bi-filetype-pdf';
				break;
			case 'zip':
				$class = 'bi-file-zip';
				break;
			default:
				$class = 'bi-file-text';
				break;
		}

		return "<a href=\"{$linkHref}\" target=\"_blank\" aria-label=\"ダウンロード\" download class=\"bi {$class} display-3\">
					<span class=\"visually-hidden\">{$desc}をダウンロードする</span>
				</a>";
	}
}

if (!function_exists('incAdminBar')) {
	function incAdminBar(): void
	{
		include_once(KONTIKI3_CORE_PATH.'/apps/common/views/incAdminBar.php');
	}
}

if (!function_exists('markdown')) {
	function markdown($content): string
	{
		include_once (KONTIKI3_CORE_PATH.'/libs/Michelf/MarkdownExtra.inc.php');
		return Michelf\MarkdownExtra::defaultTransform(htmlspecialchars_decode($content));
	}
}
