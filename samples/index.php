<?php
include_once 'Sample_Header.php';
if (!CLI) {
?>
<div class="jumbotron">
<p>Welcome to PHPWord, a library written in pure PHP that provides a set of classes to write to and read from different document file formats, i.e. Word (.docx), WordPad (.rtf), and Libre/OpenOffice Writer (.odt).</p>
<p>Please use the menu above to browse PHPWord samples.</p>
<p>
    <a class="btn btn-lg btn-primary" href="https://github.com/PHPOffice/PHPWord" role="button">Fork us on Github!</a>
    <a class="btn btn-lg btn-primary" href="http://phpword.readthedocs.org/en/develop/" role="button">Read the Docs</a>
</p>
</div>
<?
$requirements = array(
    'php' => array('PHP 5.3.0', version_compare(phpversion(), '5.3.0', '>=')),
    'zip' => array('PHP extension ZipArchive', extension_loaded('zip')),
    'xml' => array('PHP extension XML', extension_loaded('xml')),
    'gd'  => array('PHP extension GD (optional)', extension_loaded('gd')),
);
echo "<h3>Requirements</h3>";
echo "<ul>";
foreach ($requirements as $key => $value) {
    $status = $value[1] ? 'passed' : 'failed';
    echo "<li>{$value[0]} ... <span class='{$status}'>{$status}</span></li>";
}
echo "</ul>";
} // if (!CLI)
include_once 'Sample_Footer.php';
