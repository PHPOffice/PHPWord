<?php

date_default_timezone_set('UTC');

// Constantes
if (!defined('PHPWORD_TESTS_DIR_ROOT')) {
    define('PHPWORD_TESTS_DIR_ROOT', __DIR__);
}

// Includes
require_once __DIR__ . '/../Classes/PHPWord/Autoloader.php';
PHPWord_Autoloader::Register();

require_once __DIR__ . '/_inc/TestHelperDOCX.php';
require_once __DIR__ . '/_inc/XmlDocument.php';
