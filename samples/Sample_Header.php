<?php
/**
 * Header file
 */
error_reporting(E_ALL);
define('CLI', (PHP_SAPI == 'cli') ? true : false);
define('EOL', CLI ? PHP_EOL : '<br />');
require_once '../Classes/PHPWord.php';
// Return to the caller script when runs by CLI
if (CLI) {
    return;
}

// Set titles and names
$sampleFile = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$isIndexFile = ($sampleFile == 'index');
$pageHeading = str_replace('_', ' ', $sampleFile);
$pageTitle = $isIndexFile ? 'Welcome to ' : "{$pageHeading} - ";
$pageTitle .= 'PHPWord';
$pageHeading = $isIndexFile ? '' : "<h1>{$pageHeading}</h1>";
// Populate samples
$files = '';
if ($handle = opendir('.')) {
    while (false !== ($file = readdir($handle))) {
        if (preg_match('/^Sample_\d+_/', $file)) {
            $name = str_replace('_', ' ', preg_replace('/(Sample_|\.php)/', '', $file));
            $files .= "<li><a href='{$file}'>{$name}</a></li>";
        }
    }
    closedir($handle);
}
?>
<title><?php echo $pageTitle; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="bootstrap/css/phpword.css" />
</head>
<body>
<div class="container">
<div class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./">PHPWord</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown active">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Samples
                        <strong class="caret"></strong></a>
                    <ul class="dropdown-menu"><?php echo $files; ?></ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="https://github.com/PHPOffice/PHPWord">Github</a></li>
                <li><a href="http://phpword.readthedocs.org/en/develop/">Docs</a></li>
            </ul>
        </div>
    </div>
</div>
<?php echo $pageHeading; ?>
