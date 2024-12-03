<?php
namespace Kontiki3\Core\Apps\File;

use Kontiki3\Core\Models\Soft\Option;
use Kontiki3\Core\File;
use Kontiki3\Core\Input;
use Kontiki3\Core\Response;
use Kontiki3\Core\View;
use Kontiki3\Core\Csrf;
use Kontiki3\Core\Log;
use Kontiki3\Core\Pagination;

use Kontiki3\Core\Apps\File\Model;

/**
 * Controller class for managing CRUD operations for Files.
 */
abstract class Controller
{
	protected $model;
	protected $tokenname;
	protected $fileUploader;

	// Default messages
	protected $messages = [
		'invalid_request' => 'Invalid request. Please try again.',

		'validation_failed' => 'Data validation failed. Please check your input.',
		'upload_success' => 'The file has been successfully uploaded.',
		'upload_error' => 'The file could not be uploaded. Please try again.',
		'database_update_failed' => 'Failed to update the database. Please try again.',
		'file_missing' => 'No file uploaded or the file is corrupted.',
		'method_not_allowed' => 'Method not allowed.',

		'invalid_request' => 'Invalid request. Please try again.',
		'file_not_found' => 'File not found.',
		'update_success' => 'The database has been updated successfully.',
		'update_failed' => 'Failed to update the database. Please try again.',

		'file_id_required' => 'File ID is required.',
		'file_not_found' => 'File not found.',
		'file_delete_failed' => 'Failed to delete the file.',
		'db_update_failed' => 'Failed to update the database.',
		'file_delete_success' => 'File has been deleted successfully.',
		'unexpected_error' => 'An unexpected error occurred. Please try again later.'
	];

	public function __construct()
	{
		// isUserLoggedIn
		if (!isUserLoggedIn()) {
			die($this->messages['invalid_request']);
		}

		$this->tokenname = 'kontiki3_file';
		$this->fileUploader = new File(KONTIKI3_PUBLIC_PATH.'/uploads');
		$this->model = new Model();
	}

	public function ajaxGenerateCsrfToken()
	{
		$token = Csrf::generateToken($this->tokenname);
		Response::sendJson(['csrf_token' => $token]);
	}

	/**
	 * Handles file upload via an AJAX request.
	 * This method processes the uploaded file, moves it to the specified directory,
	 * and returns a JSON response indicating the result of the operation.
	 *
	 * @return void
	 *
	 * @throws Exception If there is an issue with moving the uploaded file or invalid request method.
	 */
	public function ajaxHandleFileUpload()
	{
		try {
			// CSRF Token validation
			if (!Csrf::validateToken($this->tokenname)) {
				Response::sendJson(['message' => $this->messages['invalid_request']], 405);
				return;
			}

			// Handle the file upload
			if (Input::isPostRequest()) {
				$data = $this->model->getPostData();

				// Validate data
				$errors = $this->model->validateData($data, false);
				if ($errors !== true) {
					Response::sendJson(['message' => generateAllErrorMessagesHtml($errors)], 500);
					return;
				}

				// Handle the uploaded file
				$uploadedFile = Input::files('attachment');
				if ($uploadedFile) {
					$file = [
						'name' => $uploadedFile['name'],
						'type' => $uploadedFile['type'],
						'tmp_name' => $uploadedFile['tmp_name'],
						'error' => $uploadedFile['error'],
						'size' => $uploadedFile['size'],
					];

					$result = $this->fileUploader->upload($file);

					// Move the uploaded file to the designated directory
					if ($result['success']) {
						$data['path'] = $result['path'];
						$isDbUpdate = $this->model->createItem($data);

						if ($isDbUpdate) {
							Response::sendJson(['message' => generateStatusSection('success', $this->messages['upload_success'])]);
						} else {
							Response::sendJson(['message' => generateStatusSection('error', $this->messages['database_update_failed'])], 500);
						}
					} else {
						// Error moving the file
						Response::sendJson(['message' => generateStatusSection('error', $this->messages['upload_error'])], 500);
					}
				} else {
					// Error in the upload
					Response::sendJson(['message' => generateStatusSection('error', $this->messages['file_missing'])], 400);
				}
			} else {
				// Invalid request method
				Response::sendJson(['message' => generateStatusSection('error', $this->messages['method_not_allowed'])], 405);
			}
		} catch (\Exception $e) {
			// Log unexpected errors and return a generic error message
			Log::write('Unexpected error in ajaxHandleFileUpload: ' . $e->getMessage(), 'ERROR');
			Response::sendJson(['message' => $this->messages['invalid_request']], 500);
		}
	}

	/**
	 * Handles the AJAX request to update a file's data in the database.
	 * Validates the CSRF token, retrieves the file details by ID,
	 * updates the file information, and returns a JSON response indicating success or failure.
	 *
	 * @return void
	 */
	public function ajaxHandleFileUpdate()
	{
		try {
			// Check if it's a POST request
			if (!Input::isPostRequest()) {
				return;
			}

			// CSRF Token validation
			if (!Csrf::validateToken($this->tokenname)) {
				Response::sendJson(['message' => $this->messages['invalid_request']], 405);
				return;
			}

			// Get the file ID from the POST request
			$fileId = Input::post('id', 0); // Default to 0 if no ID is provided

			// Retrieve the file details from the database using the file ID
			$data = $this->model->getItemById($fileId);

			if (!$data) {
				Response::sendJson(['message' => $this->messages['file_not_found']], 404);
				return;
			}

			// Update the description field
			$data['description'] = Input::post('description', $data['description']);

			// Update the main item
			$result = $this->update($data, $fileId);

			if ($result['success']) {
				Response::sendJson(['message' => $this->messages['update_success']]);
			} else {
				Response::sendJson(['message' => generateAllErrorMessagesHtml($result['errors'])], 500);
			}
		} catch (\Exception $e) {
			// Log unexpected errors and return a generic error message
			Log::write('Unexpected error in ajaxHandleFileUpdate: ' . $e->getMessage(), 'ERROR');
			Response::sendJson(['message' => $this->messages['invalid_request']], 500);
		}
	}

	/**
	 * Validate and process data for create or edit actions.
	 *
	 * @param array $data The input data.
	 * @param int|null $id The ID of the item to update (required for edit).
	 * @return array The result containing 'success' (bool) and 'errors' (array).
	 */
	protected function update(array $data, int $id = null)
	{
		$isEdit = true;
		$errors = $this->model->validateData($data, $isEdit, $id);

		if ($errors !== true) {
			if (isset($errors['description'])) {
					$newKey = 'eachDescription_' . $id;
					$errors[$newKey] = $errors['description'];
					unset($errors['description']);
			}

			return [
				'success' => false,
				'errors' => $errors
			];
		}

		// Process if valid
		$success = $this->model->updateItem($id, $data);

		return [
			'success' => $success,
			'errors' => $success ? [] : ["Failed to update item."]
		];
	}

	/**
	 * Handles the AJAX request to fetch the file list.
	 *
	 * This method retrieves a list of files from the model, applies security headers
	 * to the response, and then renders a view to display the file list.
	 *
	 * @return void
	 */
	public function ajaxFilelist()
	{
		// Initialize Pagination and set total items
		$page = Input::get('page', 1);
		$itemsPerPage = 10;
		$pagination = new Pagination($page, $itemsPerPage);
		$totalItems = $this->model->getTotalItems();
		$pagination->setTotalItems($totalItems);

		// Retrieve items for the current page
		$filter = (new Option())
			->setSort('created_at', 'DESC')
			->setPagination($pagination->getOffset(), $pagination->getLimit());
		$items = $this->model->getItems($filter);

		Response::applySecurityHeaders();
		View::render(
			__DIR__ . '/views/inc_filelist.php',
			[
				'items' => $items,
				'pagination' => $pagination
			]
		);
	}

	/**
	 * Handles the deletion of a file via AJAX.
	 *
	 * This method validates the CSRF token, checks the POST request for the file ID,
	 * retrieves the file from the database, deletes the corresponding file from the server,
	 * and updates the database to remove the file record.
	 * If any of these steps fail, an appropriate error message is returned as a JSON response.
	 *
	 * @return void
	 * @throws ResponseException If there is an error during the deletion process.
	 */
	public function ajaxHandleFileDelete()
	{
		try {
			// CSRF Token validation
			if (!Csrf::validateToken($this->tokenname)) {
				Response::sendJson(['message' => $this->messages['invalid_request']], 405);
				return;
			}

			// Check if it's a POST request
			if (Input::isPostRequest()) {
				// Get the file ID from the POST request
				$fileId = Input::post('id', 0); // Default to 0 if no ID is provided
				if (!$fileId) {
					Response::sendJson(['message' => $this->messages['file_id_required']], 400);
					return;
				}

				// Retrieve the file details from the database using the file ID
				$file = $this->model->getItemById($fileId);

				if (!$file) {
					Response::sendJson(['message' => $this->messages['file_not_found']], 404);
					return;
				}

				// Delete the file from the server
				$filePath = $file['path'];

				if (file_exists($filePath)) {
					if (unlink($filePath)) {
						Log::write("File deleted: " . $filePath);
					} else {
						Response::sendJson(['message' => $this->messages['file_delete_failed']], 500);
						return;
					}
				}

				// Remove the file record from the database
				$deleteSuccess = $this->model->hardDelete($fileId);
				if (!$deleteSuccess) {
					Response::sendJson(['message' => $this->messages['db_update_failed']], 500);
					return;
				}

				// Send a success response back
				Response::sendJson(['message' => $this->messages['file_delete_success']]);
			}
		} catch (\Exception $e) {
			// Log the exception details for debugging
			Log::write('Unexpected error in ajaxHandleFileDelete: ' . $e->getMessage(), 'ERROR');

			// Send a generic error response to the user
			Response::sendJson(['message' => $this->messages['unexpected_error']], 500);
		}
	}

	/**
	 * Serve the requested JavaScript file.
	 *
	 * @return void
	 */
	public function serveJs()
	{
		Response::sendJsFile(KONTIKI3_CORE_PATH . '/apps/file/js/file_manager.js');
	}
}
