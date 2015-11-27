<?php
include_once 'Sample_Header.php';

// Template processor instance creation
echo date('H:i:s'), ' Creating new TemplateProcessor instance...', EOL;
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('resources/Sample_07_TemplateCloneRow.docx');

// Variables on different parts of document
$replacements = [
    'weekday' => htmlspecialchars(date('l')), // On section/content
    'time' => htmlspecialchars(date('H:i')), // On footer
    'serverName' => htmlspecialchars(realpath(__DIR__)), // On header
];
$templateProcessor->setValuesFromArray($replacements);

// Simple table
$rows = [
    [
        'rowNumber' => 1,
        'rowValue' => 'Sun'
    ],
    [
        'rowNumber' => 2,
        'rowValue' => 'Mercury'
    ],
    [
        'rowNumber' => 3,
        'rowValue' => 'Venus'
    ],
    [
        'rowNumber' => 4,
        'rowValue' => 'Earth'
    ],
    [
        'rowNumber' => 5,
        'rowValue' => 'Mars'
    ],
    [
        'rowNumber' => 6,
        'rowValue' => 'Jupiter'
    ],
    [
        'rowNumber' => 7,
        'rowValue' => 'Saturn'
    ],
    [
        'rowNumber' => 8,
        'rowValue' => 'Uranus'
    ],
    [
        'rowNumber' => 9,
        'rowValue' => 'Neptun'
    ],
    [
        'rowNumber' => 10,
        'rowValue' => 'Pluto'
    ]
];
$templateProcessor->cloneRowFromArray('rowValue', $rows);

// Table with a spanned cell
$rows = [
    [
        'userId' => 1,
        'userFirstName' => 'James',
        'userName' => 'Taylor',
        'userPhone' => '+1 428 889 773'
    ],
    [
        'userId' => 2,
        'userFirstName' => 'Robert',
        'userName' => 'Bell',
        'userPhone' => '+1 428 889 774'
    ],
    [
        'userId' => 3,
        'userFirstName' => 'Michael',
        'userName' => 'Ray',
        'userPhone' => '+1 428 889 775'
    ]
];
$templateProcessor->cloneRowFromArray('userId', $rows);


echo date('H:i:s'), ' Saving the result document...', EOL;
$templateProcessor->saveAs('results/Sample_07_TemplateCloneRow.docx');

echo getEndingNotes(array('Word2007' => 'docx'));
if (!CLI) {
    include_once 'Sample_Footer.php';
}
