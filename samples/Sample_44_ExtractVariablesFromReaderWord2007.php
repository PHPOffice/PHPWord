<?php

include_once 'Sample_Header.php';

// Read contents
$name = basename(__FILE__, '.php');

$source = __DIR__ . "/resources/{$name}.docx";

echo date('H:i:s'), " Reading contents from `{$source}`", EOL;

$variables = \PhpOffice\PhpWord\IOFactory::extractVariables($source);

var_dump($variables);
