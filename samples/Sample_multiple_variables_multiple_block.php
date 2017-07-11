<?php
include_once 'Sample_Header.php';
// New Word document
//echo date('H:i:s') , " Create new PhpWord object" , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();


$document = $phpWord->loadTemplate('resources/Sample_TemplateBlock_MULTIPLE.docx');
$arrayVar = array('firstName' => 'Alexis', 'secondName'=>'Quiroz', 'address'=>'santiago/Chile');

$document->writeMultiBlock('CLONEME', $arrayVar);
//$document->deleteBlock('CLONEME'); //finish en


$name = 'Sample_TemplateBlock_MULTIPLE.docx';
echo date('H:i:s'), " Write to Word2007 format", EOL;
$document->saveAs($name);
rename($name, "results/{$name}");
//
echo getEndingNotes(array('Word2007' => 'docx'));
if (!CLI) {
    include_once 'Sample_Footer.php';
}
