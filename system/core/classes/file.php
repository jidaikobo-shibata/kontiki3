<?php
namespace Kontiki3\Core;

/**
 * Class for handling file uploads and deletions.
 */
class File
{
	protected $uploadDir;
	protected $allowedTypes;
	protected $maxSize;

	/**
	 * Constructor to initialize the upload directory and settings.
	 *
	 * @param string $uploadDir The directory where files will be uploaded.
	 * @param array $allowedTypes An array of allowed MIME types.
	 * @param int $maxSize The maximum allowed file size in bytes.
	 */
	public function __construct(string $uploadDir, array $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'], int $maxSize = 5000000)
	{
		$this->uploadDir = rtrim($uploadDir, '/') . '/';
		$this->allowedTypes = $allowedTypes;
		$this->maxSize = $maxSize;

		// Ensure the upload directory exists
		if (!is_dir($this->uploadDir)) {
			mkdir($this->uploadDir, 0755, true);
		}
	}

	/**
	 * Handle the file upload.
	 *
	 * @param array $file The file array from $_FILES.
	 * @return array An array with 'success' (bool), 'path' (string), 'filename' (string), and 'errors' (array).
	 */
	public function upload(array $file)
	{
		$errors = [];

		// Check for upload errors
		if ($file['error'] !== UPLOAD_ERR_OK) {
			$errors[] = "Upload error: " . $this->getUploadError($file['error']);
			return ['success' => false, 'path' => '', 'filename' => '', 'errors' => $errors];
		}

		// Validate file type
		if (!in_array($file['type'], $this->allowedTypes)) {
			$errors[] = "File type not allowed: " . $file['type'];
			return ['success' => false, 'path' => '', 'filename' => '', 'errors' => $errors];
		}

		// Validate file size
		if ($file['size'] > $this->maxSize) {
			$errors[] = "File exceeds maximum allowed size of " . ($this->maxSize / 1000000) . " MB.";
			return ['success' => false, 'path' => '', 'filename' => '', 'errors' => $errors];
		}

		// Convert filename to ASCII
		$originalName = pathinfo($file['name'], PATHINFO_FILENAME);
		$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
		$asciiName = $this->convertToAscii($originalName);
		$sanitizedFileName = $asciiName . ($extension ? ".$extension" : '');

		// Determine the target path
		$targetPath = $this->uploadDir . $sanitizedFileName;

		// Check if the file already exists, and increment the filename if necessary
		if (file_exists($targetPath)) {
			$targetPath = $this->getUniqueFileName($targetPath);
		}

		// Move the uploaded file to the target directory
		if (move_uploaded_file($file['tmp_name'], $targetPath)) {
			// Return the success status along with the final file path and the unique filename
			return ['success' => true, 'path' => $targetPath, 'filename' => basename($targetPath), 'errors' => []];
		} else {
			$errors[] = "Failed to move uploaded file.";
			return ['success' => false, 'path' => '', 'filename' => '', 'errors' => $errors];
		}
	}

	/**
	 * Convert a string to ASCII, replacing non-ASCII characters with underscores or transliterations.
	 *
	 * @param string $string The input string to convert.
	 * @return string The converted ASCII string.
	 */
	private function convertToAscii(string $string): string
	{
		// Transliterate characters to ASCII
		$ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);

		// Replace remaining non-alphanumeric characters with underscores
		$ascii = preg_replace('/[^a-zA-Z0-9]/', '_', $ascii);

		// Remove multiple underscores
		$ascii = preg_replace('/_+/', '_', $ascii);

		// Trim underscores from the start and end
		return trim($ascii, '_');
	}

	/**
	 * Get a unique file name by incrementing the filename.
	 *
	 * @param string $filePath The path of the file.
	 * @return string The unique file path.
	 */
	protected function getUniqueFileName(string $filePath)
	{
		// Extract the file name and extension
		$fileName = pathinfo($filePath, PATHINFO_FILENAME);
		$fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

		// Increment the file name until it is unique
		$newFilePath = $filePath;
		while (file_exists($newFilePath)) {
			$fileName = strIncrement($fileName);
			$newFilePath = $this->uploadDir . $fileName . '.' . $fileExtension;
		}
		return $newFilePath;
	}

	/**
	 * Delete a file from the upload directory.
	 *
	 * @param string $filePath The relative path of the file to delete.
	 * @return bool True on success, false on failure.
	 */
	public function delete(string $filePath)
	{
		$fullPath = $this->uploadDir . basename($filePath);
		if (file_exists($fullPath)) {
			return unlink($fullPath);
		}
		return false;
	}

	/**
	 * Get a human-readable message for a given upload error code.
	 *
	 * @param int $errorCode The upload error code.
	 * @return string The error message.
	 */
	protected function getUploadError(int $errorCode)
	{
		$uploadErrors = [
			UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
			UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
			UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
			UPLOAD_ERR_NO_FILE => "No file was uploaded.",
			UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
			UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
			UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload."
		];

		return $uploadErrors[$errorCode] ?? "Unknown upload error.";
	}
}
