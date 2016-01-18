<?php

// Enable error reporting.
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Start the session.
session_start();

include 'autoload.php';
$application = new JPV\Application\Application('Hello World');
echo $application->run();
