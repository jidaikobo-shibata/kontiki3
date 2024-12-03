<?php
/**
 * Message Helper Functions
 *
 * This file contains a collection of helper functions for managing and displaying
 * status messages, error messages, and notifications within the application.
 * These functions provide a convenient way to handle session messages, ensuring
 * they are displayed to the user in a consistent manner and are properly
 * cleared from the session after being shown.
 *
 * Usage:
 * Include this file in your project to utilize the message handling functions
 * throughout your application. Ensure session handling is properly configured
 * before using these functions.
 */

if (!function_exists('generateErrorAttributes')) {
	/**
	 * Generates ARIA attributes for input elements based on error messages.
	 *
	 * @param array $errors      The array of error messages.
	 * @param string $fieldName  The name of the field to check for errors.
	 * @param string $inputId    The ID of the input element.
	 * @param array $attrs       Additional attributes for the input element.
	 * @return array             The generated attributes including ARIA attributes for errors.
	 */
	function generateErrorAttributes($errors, $fieldName, $inputId, $attrs = []) {
		if (isset($errors[$fieldName])) {
			$attrs['aria-invalid'] = 'true';
			$attrs['aria-errormessage'] = 'errormessage_' . $inputId;
		}

		return $attrs;
	}
}

if (!function_exists('generateAllErrorMessagesHtml')) {
	function generateAllErrorMessagesHtml($errors, $linkText = 'Go to input') {
		$html = '';

		foreach ($errors as $fieldName => $messages) {
			// Generate the ID for the error messages
			$errorId = 'errormessage_' . $fieldName;

			// Generate error messages in list format if there are any messages
			if (!empty($messages)) {
				$html .= '<section id="' . htmlspecialchars($errorId) . '" class="alert alert-danger" role="status">';
				$html .= '<ul class="mb-0">';
				foreach ($messages as $error) {
					$html .= '<li>' . htmlspecialchars($error) . ' <a href="#' . htmlspecialchars($fieldName) . '" class="alert-link">' . htmlspecialchars($linkText) . '</a></li>';
				}
				$html .= '</ul>';
				$html .= '</section>';
			}
		}

		return $html; // Return all error messages
	}
}

if (!function_exists('generateStatusSection')) {
	/**
	 * Generates a section for displaying status messages with appropriate styling.
	 *
	 * @param string $status The status of the message (e.g., 'success', 'info', 'warning', 'danger').
	 * @param string $message The message to display within the section.
	 * @return string HTML output for the status section.
	 */
	function generateStatusSection($status, $message) {
		// Define the CSS class based on the status
		$statusClass = '';
		switch ($status) {
			case 'success':
				$statusClass = 'alert alert-success';
				break;
			case 'info':
				$statusClass = 'alert alert-info';
				break;
			case 'warning':
				$statusClass = 'alert alert-warning';
				break;
			case 'danger':
				$statusClass = 'alert alert-danger';
				break;
			default:
				$statusClass = 'alert alert-secondary'; // Default class for undefined statuses
		}

		// Generate the HTML for the status section
		$html = '<section class="' . htmlspecialchars($statusClass) . '" role="status">';
		$html .= '<p class="mb-0">' . htmlspecialchars($message) . '</p>';
		$html .= '</section>';

		return $html; // Return the generated HTML
	}
}

if (!function_exists('generateStatusSectionFromArray')) {
	/**
	 * Wrapper function that accepts an associative array and passes values to generateStatusSection.
	 *
	 * @param array $data Associative array with 'status' and 'message' keys.
	 * @return string HTML output for the status section.
	 * @throws InvalidArgumentException if required keys are missing.
	 */
	function generateStatusSectionFromArray(array $data) {
		$html = '';
		foreach ($data as $status => $message) {
			if (empty($message)) continue;
			$html.= generateStatusSection($status, $message);
		}
		return $html;
	}
}
