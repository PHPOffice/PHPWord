<?php
// error_reporting(E_ALL );

define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

require_once '../Classes/PHPWord.php';

$files = array(
    "Sample_01_SimpleText.docx",
    "Sample_02_TabStops.docx",
    "Sample_03_Sections.docx",
    "Sample_04_Textrun.docx",
    "Sample_05_Multicolumn.docx",
    "Sample_06_Footnote.docx",
    "Sample_07_TemplateCloneRow.docx",
    "Sample_08_ParagraphPagination.docx",
    "Sample_09_Tables.docx",
);

foreach ($files as $file) {
    echo '<hr />';
    echo '<p><strong>', date('H:i:s'), " Load from {$file} with contents:</strong></p>";
    unset($PHPWord);
    try {
        $PHPWord = PHPWord_IOFactory::load($file);
    } catch (Exception $e) {
        echo '<p style="color: red;">Caught exception: ',  $e->getMessage(), '</p>';
        continue;
    }
    $sections = $PHPWord->getSections();
    $countSections = count($sections);
    $pSection = 0;

    if ($countSections > 0) {
        foreach ($sections as $section) {
            $pSection++;
            echo "<p><strong>Section {$pSection}:</strong></p>";
            $elements = $section->getElements();
            foreach ($elements as $element) {
                if ($element instanceof PHPWord_Section_Text) {
                    echo '<p>' . htmlspecialchars($element->getText()) . '</p>';
                } elseif ($element instanceof PHPWord_Section_TextRun) {
                    $subelements = $element->getElements();
                    echo '<p>';
                    if (count($subelements) > 0) {
                        foreach ($subelements as $subelement) {
                            if ($subelement instanceof PHPWord_Section_Text) {
                                echo htmlspecialchars($subelement->getText());
                            }
                        }
                    }
                    echo '</p>';
                } elseif ($element instanceof PHPWord_Section_Link) {
                    echo '<p style="color: red;">Link not yet supported.</p>';
                } elseif ($element instanceof PHPWord_Section_Title) {
                    echo '<p style="color: red;">Title not yet supported.</p>';
                } elseif ($element instanceof PHPWord_Section_TextBreak) {
                    echo '<br />';
                } elseif ($element instanceof PHPWord_Section_PageBreak) {
                    echo '<p style="color: red;">Page break not yet supported.</p>';
                } elseif ($element instanceof PHPWord_Section_Table) {
                    echo '<p style="color: red;">Table not yet supported.</p>';
                } elseif ($element instanceof PHPWord_Section_ListItem) {
                    echo '<p style="color: red;">List item not yet supported.</p>';
                } elseif ($element instanceof PHPWord_Section_Image ||
                    $element instanceof PHPWord_Section_MemoryImage
                ) {
                    echo '<p style="color: red;">Image not yet supported.</p>';
                } elseif ($element instanceof PHPWord_TOC) {
                    echo '<p style="color: red;">TOC not yet supported.</p>';
                } elseif($element instanceof PHPWord_Section_Footnote) {
                    echo '<p style="color: red;">Footnote not yet supported.</p>';
                }
            }
        }
    }
}

