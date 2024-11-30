<?php
namespace Kontiki3\Core;

/**
 * Log class
 *
 * Provides logging functionality for the application.
 */
class Log
{
	// Log file path
	private static $logFile = KONTIKI3_ROOT_PATH . '/logs/app.log';

	/**
	 * Write a message to the log file.
	 *
	 * @param string|array $message The message to log (can be a string or an array).
	 * @param string $level The log level (INFO, WARNING, ERROR).
	 * @return void
	 */
	public static function write($message, $level = 'INFO')
	{
		// Create the log directory if it doesn't exist
		if (!is_dir(dirname(self::$logFile))) {
			mkdir(dirname(self::$logFile), 0777, true);
		}

		// Check if message is an array or object
		if (is_object($message)) {
			$message = method_exists($message, '__toString') ? (string) $message : json_encode($message, JSON_PRETTY_PRINT);
		} elseif (is_array($message)) {
			$message = var_export($message, true);
		}

		// Format the log entry
		$timestamp = date('Y-m-d H:i:s');
		$logEntry = "[$timestamp] [$level] $message" . PHP_EOL;

		// Write the log entry to the file
		file_put_contents(self::$logFile, $logEntry, FILE_APPEND);
	}

	/**
	 * Write a debug log entry with backtrace information.
	 *
	 * @param mixed $message The debug message to log, which can be a string, array, or object.
	 * @return void
	 */
	public static function debug($message)
	{
		// Get the call stack trace
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$traceInfo = [];

		// Format each stack trace entry
		foreach ($backtrace as $trace) {
			$file = $trace['file'] ?? '[internal function]';
			$line = $trace['line'] ?? '?';
			$function = $trace['function'];
			$class = isset($trace['class']) ? $trace['class'] . '::' : '';
			$traceInfo[] = "$file:$line - $class$function()";
		}

		// Convert trace information to string
		$traceOutput = implode("\n", $traceInfo);

		// Append trace information to the message
		$message .= "\nTrace:\n$traceOutput";

		// Pass the message to write() with DEBUG level
		self::write($message, 'DEBUG');
	}

	/**
	 * Set a custom log file path.
	 *
	 * @param string $filePath The path to the log file.
	 * @return void
	 */
	public static function setLogFile($filePath)
	{
		self::$logFile = $filePath;
	}

	/**
	 * Handle PHP errors and log them.
	 *
	 * @param int $errno The level of the error raised.
	 * @param string $errstr The error message.
	 * @param string $errfile The filename that the error was raised in.
	 * @param int $errline The line number the error was raised at.
	 * @return bool True if the error was handled, false otherwise.
	 */
	public static function errorHandler($errno, $errstr, $errfile, $errline)
	{
		$errorMessage = "PHP Error [Level $errno]: $errstr in $errfile on line $errline";
		self::write($errorMessage);
		// Returning false allows the default PHP error handler to run after this
		return false;
	}

	/**
	 * Handle uncaught exceptions and log them.
	 *
	 * @param \Throwable $exception The exception that was uncaught.
	 * @return void
	 */
	public static function exceptionHandler($exception)
	{
		$errorMessage = "Uncaught Exception: " . $exception->getMessage() .
		                " in " . $exception->getFile() . " on line " . $exception->getLine();
		self::write($errorMessage);
	}
}
