<?php
namespace Kontiki3\Core;

class View
{
	private static $headerPath = KONTIKI3_PROJECT_PATH . '/apps/common/views/header.php';
	private static $footerPath = KONTIKI3_PROJECT_PATH . '/apps/common/views/footer.php';

	/**
	 * Render a view template with optional variables.
	 *
	 * @param string $fullPath The path to the view file.
	 * @param array $variables Associative array of variables to be passed to the view.
	 * @return void
	 */
	public static function render($fullPath, $variables = [])
	{
		if (!file_exists($fullPath)) {
			throw new \Exception("View file not found: " . $fullPath);
		}

		// Extract variables to be available in the view scope
		extract($variables, EXTR_SKIP);

		// Starts output buffering to capture the output of the included view file.
		// This allows processing or manipulation of the content before sending it
		// to the final output, enabling features like filtering, caching, or error handling.
		ob_start();

		// Include the view file
		include $fullPath;

		// Retrieves and clears the buffer contents.
		// This content can be filtered or modified before final output if needed.
		$content = ob_get_clean();

		// Outputs the final content to the client.
		echo $content;
	}

	/**
	 * Include the header template.
	 *
	 * @param array $variables Associative array of variables to be passed to the view.
	 * @return void
	 */
	public static function incHeader($variables = [])
	{
		self::render(self::$headerPath, $variables);
	}

	/**
	 * Include the footer template.
	 *
	 * @param array $variables Associative array of variables to be passed to the view.
	 * @return void
	 */
	public static function incFooter($variables = [])
	{
		self::render(self::$footerPath, $variables);
	}

	/**
	 * Set a custom path for the header template.
	 *
	 * @param string $path The path to the header file.
	 * @return void
	 */
	public static function setHeaderPath($path)
	{
		self::$headerPath = $path;
	}

	/**
	 * Set a custom path for the footer template.
	 *
	 * @param string $path The path to the footer file.
	 * @return void
	 */
	public static function setFooterPath($path)
	{
		self::$footerPath = $path;
	}
}
