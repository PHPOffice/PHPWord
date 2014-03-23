<?php
\date_default_timezone_set('UTC');

// defining base dir for tests
if (!\defined('PHPWORD_TESTS_BASE_DIR')) {
    \define('PHPWORD_TESTS_BASE_DIR', \realpath(__DIR__ . '/..'));
}

// loading classes with PSR-4 autoloader
require_once __DIR__ . '/../../src/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();

spl_autoload_register(function ($class) {
    $class = ltrim($class, '\\');
    $prefix = 'PhpOffice\\PhpWord\\Tests';
    if (strpos($class, $prefix) === 0) {
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $class = 'PhpWord' . DIRECTORY_SEPARATOR . 'Tests' . DIRECTORY_SEPARATOR . '_includes' . substr($class, strlen($prefix));
        $file = __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});