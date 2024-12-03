<?php
namespace Kontiki3\Core\Controllers\Hard\Admin;

use Kontiki3\Core\Controllers\Hard\Controller as BaseController;
use Kontiki3\Core\View;
use Kontiki3\Core\Input;
use Kontiki3\Core\Session;
use Kontiki3\Core\Csrf;
use Kontiki3\Core\Pagination;
use Kontiki3\Core\Response;
use Kontiki3\Core\Log;

/**
 * Base Controller class
 */
abstract class Controller extends BaseController
{
	protected const SUCCESS_CREATE_MSG = '新規登録しました。';
	protected const ERROR_CREATE_MSG = '新規登録できませんでした。';
	protected const SUCCESS_EDIT_MSG = '編集結果を登録しました。';
	protected const ERROR_EDIT_MSG = '編集結果を登録できませんでした。';
	protected const ERROR_INVALID_CSRF = 'Invalid request. Please try again.';

	public function __construct()
	{
		$this->denyIfNotAdmin();
		parent::__construct();
	}

	/**
	 * Handle the creation of a new item.
	 */
	public function actionCreate()
	{
		$messages = [];

		// session
		$formData = Session::getOnce('form_data', []);
		$errors = Session::getOnce('errors', []);
		$messages['success'] = Session::getOnce('success_message', '');
		$messages['error'] = Session::getOnce('error_message', '');

		// populate data
		$data = $this->model->getPostData($formData);

		if (Input::hasPost()) {
			$errors = $this->processRequestData($data);
			if (empty($errors)) {
				Session::set('success_message', self::SUCCESS_CREATE_MSG);
				Response::redirect("/".$this->getAppName()."/admin/edit/".$this->model->getLastInsertId());
			} else {
				Session::set('error_message', self::ERROR_CREATE_MSG);
				Session::set('errors', $errors);
				Session::set('form_data', $data);
				Response::redirect("/".$this->getAppName()."/admin/create/");
			}
		}

		$this->renderPage($this->getCreateViewPath(), $this->getCreatePageTitle(), $errors, $messages, $data);
	}

	/**
	 * Handle the update of an existing information item.
	 *
	 * @param int $id The ID of the information item to update.
	 */
	public function actionEdit(int $id)
	{
		$messages = [];

		// Retrieve temporary data from the session
		$formData = Session::getOnce('form_data', []);
		$errors = Session::getOnce('errors', []);
		$messages['success'] = Session::getOnce('success_message', '');
		$messages['error'] = Session::getOnce('error_message', '');

		// Fetch the item by ID
		$item = $this->model->getItemById($id);
		if (!$item) {
			Response::send404();
			return;
		}

		// Populate form data with default values from existing item data
		$data = $this->model->getPostData($formData + $item);

		// Handle POST request
		if (Input::hasPost()) {
			$errors = $this->processRequestData($data, true, $id);
			if (empty($errors)) {
				// Success: Set success message and redirect
				Session::set('success_message', self::SUCCESS_EDIT_MSG);
				Response::redirect("/".$this->getAppName()."/admin/edit/{$id}");
			} else {
				// Failure: Set error message and data in session, then redirect
				Session::set('error_message', self::ERROR_EDIT_MSG);
				Session::set('errors', $errors);
				Session::set('form_data', $data);
				Response::redirect("/".$this->getAppName()."/admin/edit/{$id}");
			}
		}

		// Render page for initial display or after redirection
		$this->renderPage($this->getEditViewPath(), $this->getEditPageTitle(), $errors, $messages, $data, $id, $item);
	}

	/**
	 * Handle hard deletion of an information item.
	 *
	 * @param int $id The ID of the information item to hard delete.
	 */
	public function actionHardDelete(int $id)
	{
		if ($this->model->hardDelete($id)) {
			Response::redirect('/'.$this->getAppName().'/admin/');
		} else {
			Response::send500('Failed to delete the item.');
		}
	}

	/**
	 * Process the request data for create or edit actions.
	 *
	 * @param array $data The data to update.
	 * @param bool $isEdit Whether this is an edit action.
	 * @param int|null $id The item ID.
	 * @return array The array of errors, empty if successful.
	 */
	protected function processRequestData(array &$data, bool $isEdit = false, int $id = null): array
	{
		$errors = [];
		if (Input::hasPost()) {
			if (!Csrf::validateToken($this->tokenname)) {
				$errors[] = [self::ERROR_INVALID_CSRF];
			} else {
				$data = $this->model->getPostData($data);
				$result = $this->update($data, $isEdit, $id);
				if (!$result['success']) {
					$errors = $result['errors'];
				}
			}
		}
		return $errors;
	}

	/**
	 * Render the page with the provided parameters.
	 *
	 * @param string $viewPath Path to the view.
	 * @param string $pageTitle Title of the page.
	 * @param array $errors List of errors.
	 * @param array $messages List of messages.
	 * @param array $data Data to display.
	 * @param int|null $id The item ID (for edit actions).
	 * @param array|null $item The item data (for edit actions).
	 */
	protected function renderPage(string $viewPath, string $pageTitle, array $errors, array $messages, array $data, int $id = null, array $item = null): void
	{
		$token = Csrf::generateToken($this->tokenname);
		$params = [
			'controller' => $this,
			'pagetitle' => $pageTitle,
			'errors' => $errors,
			'messages' => $messages,
			'token' => $token,
			'data' => $data
		];

		if ($id !== null) {
			$params['id'] = $id;
		}
		if ($item !== null) {
			$params['item'] = $item;
		}

		Response::renderStandardPage($viewPath, $params, $pageTitle);
	}

	abstract protected function getCreatePageTitle(): string;
	abstract protected function getCreateViewPath(): string;
	abstract protected function getEditPageTitle(): string;
	abstract protected function getEditViewPath(): string;

	/**
	 * Validate and process data for create or edit actions.
	 *
	 * @param array $data The input data.
	 * @param bool $isEdit Whether this is an edit action.
	 * @param int|null $id The item ID.
	 * @return array The result with 'success' (bool) and 'errors' (array).
	 */
	protected function update(array $data, bool $isEdit = false, int $id = null): array
	{
		$errors = $this->model->validateData($data, $isEdit, $id);

		if ($errors !== true) {
			return ['success' => false, 'errors' => $errors];
		}

		$success = $isEdit && $id !== null ? $this->model->updateItem($id, $data) : $this->model->createItem($data);

		return [
			'success' => $success,
			'errors' => $success ? [] : ["Failed to " . ($isEdit ? "update" : "create") . " item."]
		];
	}
}
