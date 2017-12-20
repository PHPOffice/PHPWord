<?php
include_once 'Sample_Header.php';

use PhpOffice\PhpWord\Shared\Converter;

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Define styles
$phpWord->addTitleStyle(1, array('size' => 14, 'bold' => true), array('keepNext' => true, 'spaceBefore' => 240));
$phpWord->addTitleStyle(2, array('size' => 14, 'bold' => true), array('keepNext' => true, 'spaceBefore' => 240));

// 2D charts
$section = $phpWord->addSection();
$section->addTitle('2D charts', 1);
$section = $phpWord->addSection(array('colsNum' => 2, 'breakType' => 'continuous'));

$chartTypes = array('pie', 'doughnut', 'bar', 'column', 'line', 'area', 'scatter', 'radar');
$twoSeries = array('bar', 'column', 'line', 'area', 'scatter', 'radar');
$threeSeries = array('bar', 'line');
$categories = array('A', 'B', 'C', 'D', 'E');
$series1 = array(1, 3, 2, 5, 4);
$series2 = array(3, 1, 7, 2, 6);
$series3 = array(8, 3, 2, 5, 4);

foreach ($chartTypes as $chartType) {
    $section->addTitle(ucfirst($chartType), 2);
    $chart = $section->addChart($chartType, $categories, $series1);
    $chart->getStyle()->setWidth(Converter::inchToEmu(2.5))->setHeight(Converter::inchToEmu(2));
    if (in_array($chartType, $twoSeries)) {
        $chart->addSeries($categories, $series2);
    }
    if (in_array($chartType, $threeSeries)) {
        $chart->addSeries($categories, $series3);
    }
    $section->addTextBreak();
}

// 3D charts
$section = $phpWord->addSection(array('breakType' => 'continuous'));
$section->addTitle('3D charts', 1);
$section = $phpWord->addSection(array('colsNum' => 2, 'breakType' => 'continuous'));

$chartTypes = array('pie', 'bar', 'column', 'line', 'area');
$multiSeries = array('bar', 'column', 'line', 'area');
$style = array('width' => Converter::cmToEmu(5), 'height' => Converter::cmToEmu(4), '3d' => true);
foreach ($chartTypes as $chartType) {
    $section->addTitle(ucfirst($chartType), 2);
    $chart = $section->addChart($chartType, $categories, $series1, $style);
    if (in_array($chartType, $multiSeries)) {
        $chart->addSeries($categories, $series2);
        $chart->addSeries($categories, $series3);
    }
    $section->addTextBreak();
}

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
