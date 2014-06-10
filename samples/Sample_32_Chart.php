<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), " Create new PhpWord object", EOL;

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection(array('colsNum' => 2));
$phpWord->addTitleStyle(1, array('size' => 14, 'bold' => true), array('keepNext' => true, 'spaceBefore' => 240));

$charts = array('pie', 'doughnut', 'line', 'area', 'scatter', 'bar', 'radar');
$labels = array('A', 'B', 'C', 'D', 'E');
$data = array(1, 3, 2, 5, 4);

foreach ($charts as $chart) {
    $section->addTitle(ucfirst($chart), 1);
    $section->addChart($chart, $labels, $data);
    $section->addTextBreak();
}

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
