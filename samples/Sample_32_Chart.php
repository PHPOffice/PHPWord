<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), " Create new PhpWord object", EOL;

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection(array('colsNum' => 2));
$phpWord->addTitleStyle(1, array('size' => 14, 'bold' => true), array('keepNext' => true, 'spaceBefore' => 240));

$chartTypes = array('pie', 'doughnut', 'bar', 'line', 'area', 'scatter', 'radar');
$twoSeries = array('bar', 'line', 'area', 'scatter', 'radar');
$threeSeries = array('bar', 'line');
$categories = array('A', 'B', 'C', 'D', 'E');
$series1 = array(1, 3, 2, 5, 4);
$series2 = array(3, 1, 7, 2, 6);
$series3 = array(8, 3, 2, 5, 4);

foreach ($chartTypes as $chartType) {
    $section->addTitle(ucfirst($chartType), 1);
    $chart = $section->addChart($chartType, $categories, $series1);
    if (in_array($chartType, $twoSeries)) {
        $chart->addSeries($categories, $series2);
    }
    if (in_array($chartType, $threeSeries)) {
        $chart->addSeries($categories, $series3);
    }
    $section->addTextBreak();
}

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
