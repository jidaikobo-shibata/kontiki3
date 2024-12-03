<?php
/**
 * HTML Element Generation Helper Functions
 *
 * This file contains a collection of helper functions for generating various HTML elements.
 * Each function allows for flexible customization of attributes and content,
 * simplifying the creation of HTML structures for dynamic web pages.
 *
 * The core function, createHtmlElement(), serves as a foundational method to generate HTML tags
 * with specified attributes and content, while additional functions create specific elements
 * like forms, inputs, buttons, lists, tables, and more.
 */

if (!function_exists('createHtmlElement')) {
	/**
	 * Generates a flexible HTML element with customizable tag name, content, and attributes
	 *
	 * @param string $tag       The name of the HTML tag (e.g., 'div', 'span', 'a')
	 * @param string $content   The inner content of the tag (for self-closing tags, leave empty)
	 * @param array $attrs      Attributes (e.g., ['class' => 'my-class', 'id' => 'my-id'])
	 * @param bool $selfClosing Whether the tag is self-closing (e.g., for <img>, <input>, etc.)
	 * @param bool $escContent  Whether to escape the content (default: true)
	 * @return string           The generated HTML for the element
	 */
	function createHtmlElement($tag, $content = '', $attrs = [], $selfClosing = false, $escContent = true) {
		// Start with the opening tag and add any attributes
		$element = '<' . htmlspecialchars($tag);
		foreach ($attrs as $key => $value) {
			$element .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
		}

		// Handle self-closing tags
		if ($selfClosing) {
			$element .= '>';
		} else {
			// Add content and close the tag
			$element .= '>' . ($escContent ? htmlspecialchars($content) : $content) . '</' . htmlspecialchars($tag) . '>';
		}

		return $element;
	}
}

if (!function_exists('createForm')) {
	/**
	 * Generates a flexible form opening tag with customizable attributes
	 *
	 * @param string $action   The form's action URL
	 * @param string $method   The HTTP method (e.g., 'post', 'get')
	 * @param array $attrs     Additional attributes (e.g., ['id' => 'myForm', 'class' => 'form-horizontal'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the opening <form> tag
	 */
	function createForm($action = '', $method = 'post', $attrs = [], $escContent = true) {
		// Set action and method as standard attributes
		$attrs['action'] = $action;
		$attrs['method'] = $method;

		// Use createHtmlElement to generate the opening <form> tag
		return createHtmlElement('form', '', $attrs, false, $escContent);
	}
}

if (!function_exists('closeForm')) {
	/**
	 * Generates the closing </form> tag
	 *
	 * @return string The closing form tag
	 */
	function closeForm() {
		return '</form>';
	}
}

if (!function_exists('createInput')) {
	/**
	 * Generates a flexible input element with customizable attributes
	 *
	 * @param string $name     The name attribute of the input
	 * @param string $type     The type attribute of the input (default is 'text')
	 * @param string $value    The initial value of the input
	 * @param array $attrs     Additional attributes (e.g., ['id' => 'username', 'class' => 'form-control'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the input element
	 */
	function createInput($name, $type = 'text', $value = '', $attrs = [], $escContent = true) {
		// Add standard attributes to the $attrs array
		$attrs['name'] = $name;
		$attrs['type'] = $type;
		$attrs['value'] = $value;

		// Use createHtmlElement to generate the input element
		return createHtmlElement('input', '', $attrs, true, $escContent);
	}
}

if (!function_exists('createTextarea')) {
	/**
	 * Generates a flexible textarea element with customizable attributes
	 *
	 * @param string $name     The name attribute of the textarea
	 * @param string $value    The initial content of the textarea
	 * @param array $attrs     Additional attributes (e.g., ['id' => 'message', 'class' => 'form-control'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the textarea element
	 */
	function createTextarea($name, $value = '', $attrs = [], $escContent = true) {
		// Set name attribute in the $attrs array
		$attrs['name'] = $name;

		// Use createHtmlElement to generate the textarea element
		return createHtmlElement('textarea', $value, $attrs, false, $escContent);
	}
}

if (!function_exists('createSelect')) {
	/**
	 * Generates a flexible select element with customizable attributes and options
	 *
	 * @param string $name     The name attribute of the select
	 * @param array $options   An associative array of options (e.g., ['value1' => 'Label 1', 'value2' => 'Label 2'])
	 * @param string $selected The value of the initially selected option
	 * @param array $attrs     Additional attributes (e.g., ['id' => 'dropdown', 'class' => 'form-select'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the select element
	 */
	function createSelect($name, $options = [], $selected = '', $attrs = [], $escContent = true) {
		// Set the name attribute in the $attrs array
		$attrs['name'] = $name;

		// Generate options HTML
		$optionsHtml = '';
		foreach ($options as $value => $label) {
			// Check if the option should be selected
			$optionAttributes = ['value' => ($escContent ? htmlspecialchars($value) : $value)];

			// Not strictly type-strict
			// if ($value === $selected) {
			if ($value == $selected) {
				$optionAttributes['selected'] = 'selected';
			}
			// Escape the label if needed, but don't escape the value
			$labelHtml = $escContent ? htmlspecialchars($label) : $label;
			$optionsHtml .= createHtmlElement('option', $labelHtml, $optionAttributes, false, false);
		}

		// Use createHtmlElement to generate the select element, passing the options HTML as content
		return createHtmlElement('select', $optionsHtml, $attrs, false, false);
	}
}

if (!function_exists('createButton')) {
	/**
	 * Generates a flexible button element with customizable attributes
	 *
	 * @param string $label    The button's label (text content)
	 * @param string $type     The button's type attribute (default is 'button')
	 * @param array $attrs     Additional attributes (e.g., ['class' => 'btn btn-primary', 'id' => 'submitBtn'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the button element
	 */
	function createButton($label = 'Submit', $type = 'button', $attrs = [], $escContent = true) {
		// Set the type attribute in the $attrs array
		$attrs['type'] = $type;

		// Use createHtmlElement to generate the button element
		return createHtmlElement('button', $label, $attrs, false, $escContent);
	}
}

if (!function_exists('createLabel')) {
	/**
	 * Generates a flexible label element with customizable attributes
	 *
	 * @param string $text     The text content of the label
	 * @param string $for      The id of the associated input element (optional)
	 * @param array $attrs     Additional attributes (e.g., ['class' => 'form-label', 'aria-hidden' => 'true'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the label element
	 */
	function createLabel($text, $for = '', $attrs = [], $escContent = true) {
		// Set the for attribute if provided
		if (!empty($for)) {
			$attrs['for'] = $for;
		}

		// Use createHtmlElement to generate the label element
		return createHtmlElement('label', $text, $attrs, false, $escContent);
	}
}

if (!function_exists('createFieldset')) {
	/**
	 * Generates a fieldset element with an optional legend and inner content
	 *
	 * @param string $legend   The legend text for the fieldset (optional)
	 * @param string $content  The HTML content inside the fieldset (e.g., form inputs)
	 * @param array $attrs     Additional attributes for the fieldset (e.g., ['class' => 'form-group'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the fieldset element
	 *
	 * @example Generate a fieldset with legend:
	 *     echo createFieldset('Personal Information', '<input type="text" name="name">');
	 *
	 * @example Generate a fieldset without legend:
	 *     echo createFieldset('', '<input type="text" name="name">');
	 */
	function createFieldset($legend = '', $content = '', $attrs = [], $escContent = true) {
		// If a legend is provided, create the legend element
		$legendHtml = $legend ? createHtmlElement('legend', $legend, [], false, $escContent) : '';

		// Combine legend and content, then wrap in a fieldset
		$fieldsetContent = $legendHtml . $content;
		return createHtmlElement('fieldset', $fieldsetContent, $attrs, false, $escContent);
	}
}

if (!function_exists('createList')) {
	/**
	 * Generates a flexible unordered (ul) or ordered (ol) list with customizable attributes and nested lists
	 *
	 * @param string $type     The type of the list ('ul' or 'ol')
	 * @param array $items     An array of list items. Each item can be a string, or an associative array with 'content', 'attributes', and optional 'sublist' and 'sublist_type'
	 * @param array $attrs     Additional attributes for the list (e.g., ['class' => 'my-list'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the list element
	 *
	 * @example Simple unordered list:
	 *     echo createList('ul', ['Item 1', 'Item 2', 'Item 3']);
	 *
	 * @example Nested list with different sublist type:
	 *     echo createList('ul', [
	 *         ['content' => 'Item 1'],
	 *         ['content' => 'Item 2', 'sublist' => [
	 *             ['content' => 'Subitem 2.1'],
	 *             ['content' => 'Subitem 2.2']
	 *         ], 'sublist_type' => 'ol'], // Set nested list as ordered list (ol)
	 *         ['content' => 'Item 3']
	 *     ]);
	 */
	function createList($type = 'ul', $items = [], $attrs = [], $escContent = true) {
		// Validate list type
		$type = in_array($type, ['ul', 'ol']) ? $type : 'ul';

		// Generate the list items
		$listItems = '';
		foreach ($items as $item) {
			// If item is an array with 'content' and optionally 'sublist'
			if (is_array($item) && isset($item['content'])) {
				$content = $item['content'];
				$itemAttributes = $item['attributes'] ?? [];

				// Check for nested 'sublist' and generate recursively if it exists
				if (isset($item['sublist']) && is_array($item['sublist'])) {
					// Determine the sublist type (default to 'ul' if not specified)
					$sublistType = $item['sublist_type'] ?? 'ul';
					$content .= createList($sublistType, $item['sublist'], [], $escContent);
				}
			} else {
				// Otherwise, treat item as a string
				$content = $item;
				$itemAttributes = [];
			}
			// Generate each <li> using createHtmlElement
			$listItems .= createHtmlElement('li', $content, $itemAttributes, false, $escContent);
		}

		// Use createHtmlElement to generate the <ul> or <ol> element with list items as content
		return createHtmlElement($type, $listItems, $attrs, false, $escContent);
	}
}

if (!function_exists('createDefinitionList')) {
	/**
	 * Generates a flexible definition list (dl) with customizable attributes and optional nested lists
	 *
	 * @param array $items     An array of definition list items, where each item is an associative array with 'term', 'description', and optional 'attributes'
	 * @param array $attrs     Additional attributes for the dl element (e.g., ['class' => 'definition-list'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the definition list
	 *
	 * @example Simple definition list:
	 *     echo createDefinitionList([
	 *         ['term' => 'HTML', 'description' => 'A markup language for creating web pages.'],
	 *         ['term' => 'CSS', 'description' => 'A style sheet language for styling HTML documents.']
	 *     ]);
	 *
	 * @example Nested definition list:
	 *     echo createDefinitionList([
	 *         ['term' => 'Programming Languages', 'description' => createDefinitionList([
	 *             ['term' => 'Python', 'description' => 'A high-level programming language.'],
	 *             ['term' => 'JavaScript', 'description' => 'A language for adding interactivity to web pages.']
	 *         ])]
	 *     ]);
	 */
	function createDefinitionList($items = [], $attrs = [], $escContent = true) {
		// Generate the list items
		$listItems = '';
		foreach ($items as $item) {
			// Extract term and description, and handle optional attributes
			$term = $item['term'] ?? '';
			$description = $item['description'] ?? '';
			$termAttributes = $item['term_attributes'] ?? [];
			$descAttributes = $item['desc_attributes'] ?? [];

			// Generate <dt> and <dd> elements
			$listItems .= createHtmlElement('dt', $term, $termAttributes, false, $escContent);
			$listItems .= createHtmlElement('dd', $description, $descAttributes, false, $escContent);
		}

		// Use createHtmlElement to generate the <dl> element with the list items as content
		return createHtmlElement('dl', $listItems, $attrs, false, $escContent);
	}
}

if (!function_exists('createTable')) {
	/**
	 * Generates a complete table element with headers and data rows
	 *
	 * @param array $headers   An array of header labels (e.g., ['Name', 'Age', 'Email'])
	 * @param array $data      A 2D array of data rows (e.g., [['John', 25, 'john@example.com'], ['Jane', 30, 'jane@example.com']])
	 * @param array $attrs     Additional attributes for the table (e.g., ['class' => 'table'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the table
	 */
	function createTable($headers = [], $data = [], $attrs = [], $escContent = true) {
		// Generate header row
		$headerHtml = '';
		foreach ($headers as $header) {
			$headerHtml .= createHtmlElement('th', $header, [], false, $escContent);
		}
		$headerRow = createHtmlElement('tr', $headerHtml, [], false, $escContent);

		// Generate data rows
		$rowsHtml = '';
		foreach ($data as $row) {
			$rowHtml = '';
			foreach ($row as $cell) {
				$rowHtml .= createHtmlElement('td', $cell, [], false, $escContent);
			}
			$rowsHtml .= createHtmlElement('tr', $rowHtml, [], false, $escContent);
		}

		// Combine header row and data rows
		$tableContent = createHtmlElement('thead', $headerRow, [], false, $escContent) . createHtmlElement('tbody', $rowsHtml, [], false, $escContent);

		// Generate the complete table
		return createHtmlElement('table', $tableContent, $attrs, false, $escContent);
	}
}

if (!function_exists('createTableRow')) {
	/**
	 * Generates a table row (<tr>) with a specified set of cells, supporting header rows and row headers
	 *
	 * @param array $cells          An array of cell contents for the row (e.g., ['Name', 'Age', 'Email'])
	 * @param bool $isHeaderRow     If true, all cells in the row will be header cells (<th>)
	 * @param bool $firstCellHeader If true, only the first cell will be a header cell (<th>), with the rest as <td> (default: false)
	 * @param bool $escContent      Whether to escape the content (default: true)
	 * @return string               The generated HTML for the table row
	 *
	 * @example Generate a simple data row:
	 *     echo createTableRow(['John Doe', '30', 'john@example.com']);
	 *
	 * @example Generate a header row:
	 *     echo createTableRow(['Name', 'Age', 'Email'], true);
	 *
	 * @example Generate a row with a row header (first cell as <th>):
	 *     echo createTableRow(['Row Header', 'Data 1', 'Data 2'], false, true);
	 */
	function createTableRow($cells = [], $isHeaderRow = false, $firstCellHeader = false, $escContent = true) {
		$rowContent = '';
		foreach ($cells as $index => $cell) {
			// Determine if the cell should be a header cell
			$isHeader = $isHeaderRow || ($firstCellHeader && $index === 0);
			$tag = $isHeader ? 'th' : 'td';
			$rowContent .= createHtmlElement($tag, $cell, [], false, $escContent);
		}
		return createHtmlElement('tr', $rowContent, [], false, $escContent);
	}
}

if (!function_exists('createLink')) {
	/**
	 * Generates an anchor (<a>) element with customizable attributes
	 *
	 * @param string $href     The URL for the link
	 * @param string $text     The link text
	 * @param array $attrs     Additional attributes (e.g., ['target' => '_blank', 'rel' => 'noopener'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the anchor element
	 */
	function createLink($href, $text, $attrs = [], $escContent = true) {
		$attrs['href'] = $href;
		return createHtmlElement('a', $text, $attrs, false, $escContent);
	}
}

if (!function_exists('createImage')) {
	/**
	 * Generates an image (<img>) element with customizable attributes
	 *
	 * @param string $src      The source URL of the image
	 * @param string $alt      The alt text for the image
	 * @param array $attrs     Additional attributes (e.g., ['class' => 'responsive', 'width' => '100'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the image element
	 */
	function createImage($src, $alt = '', $attrs = [], $escContent = true) {
		$attrs['src'] = $src;
		$attrs['alt'] = $alt;
		return createHtmlElement('img', '', $attrs, true, $escContent);
	}
}

if (!function_exists('createIframe')) {
	/**
	 * Generates an iframe (<iframe>) element with customizable attributes
	 *
	 * @param string $src      The source URL for the iframe
	 * @param array $attrs     Additional attributes (e.g., ['width' => '600', 'height' => '400', 'frameborder' => '0'])
	 * @param bool $escContent Whether to escape the content (default: true)
	 * @return string          The generated HTML for the iframe element
	 */
	function createIframe($src, $attrs = [], $escContent = true) {
		$attrs['src'] = $src;
		return createHtmlElement('iframe', '', $attrs, false, $escContent);
	}
}

if (!function_exists('createMetaTag')) {
	/**
	 * Generates a meta element with customizable attributes
	 *
	 * @param string $name      The meta name or property
	 * @param string $content   The content attribute value
	 * @param bool $isProperty  If true, uses 'property' instead of 'name' (useful for Open Graph tags)
	 * @param bool $escContent  Whether to escape the content (default: true)
	 * @return string           The generated HTML for the meta element
	 */
	function createMetaTag($name, $content, $isProperty = false, $escContent = true) {
		$attributeName = $isProperty ? 'property' : 'name';
		$attrs = [$attributeName => $name, 'content' => $content];
		return createHtmlElement('meta', '', $attrs, true, $escContent);
	}
}
