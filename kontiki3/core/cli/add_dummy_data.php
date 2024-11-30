<?php
namespace Kontiki3\Core;

require_once dirname(__DIR__, 2) . '/kontiki3.php';

$options = getopt('', ['model::', 'num::']);

// Parse options
$num = isset($options['num']) ? (int)$options['num'] : 10; // Default 10 items
$modelName = $options['model'] ?? '';

if (empty($modelName)) {
	die("Error: You must specify a model using the --model option.\n");
}

$modelName = str_replace('/', '\\', $modelName);
$modelClass = '\\Kontiki3\\' . ucfirst($modelName);

if (!class_exists($modelClass)) {
	die("Error: Model class '{$modelClass}' does not exist.\n");
}

try {
	// Attempt to instantiate the model dynamically
	$model = new $modelClass();
} catch (Error $e) {
	// Handle errors during instantiation (e.g., missing constructor dependencies)
	die("Error: Failed to instantiate model class '{$modelClass}'. Details: {$e->getMessage()}\n");
}

function generateRandomString(int $length = 16): string
{
	return substr(bin2hex(random_bytes($length)), 0, $length);
}

// insert data

for ($n = 0; $n < $num; $n++)
{
	$data = [];
	foreach ($model->getProperties() as $field => $values)
	{
		$data[$field] = (!empty($values['default'])) ? $values['default'] : generateRandomString();
	}
//Log::write($data);
	$model->createItem($data);
}

//Log::write($model->getProperties());

echo "Model '{$modelClass}' instantiated with {$num} items to process.\n";
