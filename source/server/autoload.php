<?php

// The absolute paths to the class files.
define('MODULES_PATH', 'source' . DIRECTORY_SEPARATOR . 'server' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR);
define('VENDOR_PATH', 'source' . DIRECTORY_SEPARATOR . 'server' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR);

/**
 * The autoload function. Loads class files from modules and vendor folder.
 *
 * @param string $className
 *
 * @throws \Exception
 */
function __autoload($className)
{
	$partialFilePath = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

	// Try loading the class from the modules folder.
	$filePath = MODULES_PATH . $partialFilePath;
	if (file_exists($filePath))
	{
		include $filePath;
		return;
	}

	// Try loading the class from vendor folder.
	$filePath = VENDOR_PATH . $partialFilePath;
	if (file_exists($filePath))
	{
		include $filePath;
		return;
	}

	// The class file was not found in modules or vendor folder.
	throw new \Exception('Could not load class. File "' . $partialFilePath . '" does not exist.');
}
