<?php

// The absolute paths to the class files.
define('MODULES_PATH', 'source\\server\\modules\\');
define('VENDOR_PATH', 'source\\server\\vendor\\');

/**
 * The autoload function. Loads class files from modules and vendor folder.
 *
 * @param string $className
 *
 * @throws \Exception
 */
function __autoload($className)
{
	// Try loading the class from the modules folder.
	$fileName = MODULES_PATH . $className . '.php';
	if (file_exists($fileName))
	{
		include $fileName;
		return;
	}

	// Try loading the class from vendor folder.
	$fileName = VENDOR_PATH . $className . '.php';
	if (file_exists($fileName))
	{
		include $fileName;
		return;
	}

	// The class file was not found in modules or vendor folder.
	throw new \Exception('Could not load class. File "' . $fileName . '" does not exist.');
}
