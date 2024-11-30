<?php
namespace Kontiki3\File;

use Kontiki3\Core\Apps\File\Controller as FileController;
use Kontiki3\Core\File;
use Kontiki3\Core\Log;
use Kontiki3\Core\Response;

class Controller extends FileController
{
	protected $messages = [
		'validation_failed' => 'データの検証に失敗しました。入力内容を確認してください。',
		'upload_success' => 'ファイルが正常にアップロードされました。',
		'upload_error' => 'ファイルをアップロードできませんでした。再試行してください。',
		'database_update_failed' => 'データベースの更新に失敗しました。再試行してください。',
		'file_missing' => 'ファイルがアップロードされていないか、破損しています。',
		'method_not_allowed' => '許可されていないメソッドです。',

		'file_not_found' => 'ファイルが見つかりません。',
		'update_success' => 'データベースを正常に更新しました。',
		'update_failed' => 'データベースの更新に失敗しました。もう一度お試しください。',

		'invalid_request' => 'リクエストが無効です。もう一度お試しください。',
		'file_id_required' => 'ファイルIDが必要です。',
		'file_not_found' => 'ファイルが見つかりません。',
		'file_delete_failed' => 'ファイルの削除に失敗しました。',
		'db_update_failed' => 'データベースの更新に失敗しました。',
		'file_delete_success' => 'ファイルは正常に削除されました。',
		'unexpected_error' => '予期しないエラーが発生しました。後でもう一度お試しください。'
	];

	public function __construct()
	{
		// isUserLoggedIn
		if (!isUserLoggedIn()) {
			die($this->messages['invalid_request']);
		}

		$this->tokenname = 'kontiki3_file';
		$this->fileUploader = new File(
			KONTIKI3_PUBLIC_PATH.'/uploads',
			KONTIKI3_ALLOWED_FILES
		);
		$this->model = new Model();
	}

	/**
	 * Serve the requested JavaScript file.
	 *
	 * @return void
	 */
	public function serveJsFileManagerInstance()
	{
		Response::sendJsFile(__DIR__ . '/js/file_manager_instance.js');
	}
}
