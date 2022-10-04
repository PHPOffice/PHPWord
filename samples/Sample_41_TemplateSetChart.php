<?php

use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\Shared\Converter;

include_once 'Sample_Header.php';

// Template processor instance creation
echo date('H:i:s'), ' Creating new TemplateProcessor instance...', EOL;
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('resources/Sample_41_TemplateSetChart.docx');

$chartTypes = ['pie', 'doughnut', 'bar', 'column', 'line', 'area', 'scatter', 'radar', 'stacked_bar', 'percent_stacked_bar', 'stacked_column', 'percent_stacked_column'];
$twoSeries = ['bar', 'column', 'line', 'area', 'scatter', 'radar', 'stacked_bar', 'percent_stacked_bar', 'stacked_column', 'percent_stacked_column'];
$threeSeries = ['bar', 'line'];

$categories = ['A', 'B', 'C', 'D', 'E'];
$series1 = [1, 3, 2, 5, 4];
$series2 = [3, 1, 7, 2, 6];
$series3 = [8, 3, 2, 5, 4];

$i = 0;
foreach ($chartTypes as $chartType) {
    $chart = new Chart($chartType, $categories, $series1);

    if (in_array($chartType, $twoSeries)) {
        $chart->addSeries($categories, $series2);
    }
    if (in_array($chartType, $threeSeries)) {
        $chart->addSeries($categories, $series3);
    }

    $chart->getStyle()
        ->setWidth(Converter::inchToEmu(3))
        ->setHeight(Converter::inchToEmu(3));

    $templateProcessor->setChart("chart{$i}", $chart);
    ++$i;
}

echo date('H:i:s'), ' Saving the result document...', EOL;
$templateProcessor->saveAs('results/Sample_41_TemplateSetChart.docx');

echo getEndingNotes(['Word2007' => 'docx'], 'results/Sample_41_TemplateSetChart.docx');
if (!CLI) {
    include_once 'Sample_Footer.php';
}
