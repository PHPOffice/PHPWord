<?php

namespace PhpOffice\PhpWordTests\Writer;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PHPUnit\Framework\TestCase;

class WPSTest extends TestCase
{
    public function testIndependentImportOfEmptyDocumentAndPageBreaks(): void
    {
        self::assertStringContainsString('endDocument()', $this->import(new PhpWord()));
        $document = new PhpWord();
        $section = $document->addSection();
        $section->addText("Ελληνικά\t日本語");
        $section->addPageBreak();
        $section->addText('Second page');
        $document->addSection()->addText('Third page');
        $output = $this->import($document);
        foreach (['insertText(text: Ελληνικά)', 'insertTab()', 'insertText(text: 日本語)', 'insertText(text: Second page)', 'insertText(text: Third page)'] as $expected) {
            self::assertStringContainsString($expected, $output);
        }
        self::assertSame(2, substr_count($output, 'fo:break-before: page'));
    }

    public function testIndependentImportPreservesDecorationsAndHangingIndent(): void
    {
        $document = new PhpWord();
        $run = $document->addSection()->addTextRun(['indentation' => ['left' => 720, 'right' => 360, 'hanging' => 180]]);
        $run->addText('single', ['underline' => 'single']);
        $run->addText('double', ['underline' => 'dbl']);
        $run->addText('raised', ['superScript' => true]);
        $run->addText('lowered', ['subScript' => true]);
        $run->addText('removed', ['strikethrough' => true]);
        $run->addText('plain');
        $output = $this->import($document);
        foreach (['fo:margin-left: 0.5000in', 'fo:margin-right: 0.2500in', 'fo:text-indent: -0.1250in', 'style:text-underline-type: single', 'style:text-underline-type: double', 'style:text-position: super', 'style:text-position: sub', 'style:text-line-through-type: single'] as $expected) {
            self::assertStringContainsString($expected, $output);
        }
        self::assertSame(2, substr_count($output, 'style:text-underline-type:'));
        self::assertSame(2, substr_count($output, 'style:text-position:'));
        self::assertSame(1, substr_count($output, 'style:text-line-through-type:'));
    }

    public function testIndependentImportPreservesFormattingAndPageLayout(): void
    {
        $document = new PhpWord();
        $document->addTitleStyle(1, ['name' => 'Arial', 'size' => 18, 'bold' => true], ['alignment' => 'center']);
        $document->addFontStyle('WorksEmphasis', ['name' => 'Courier New', 'size' => 14, 'italic' => true, 'color' => 'C03010']);
        $section = $document->addSection(['orientation' => 'landscape', 'marginTop' => 720, 'marginLeft' => 1080]);
        $section->addTitle('Works API demo', 1);
        $run = $section->addTextRun(['alignment' => 'right']);
        $run->addText('Bonjour — café ', ['name' => 'Arial', 'size' => 12, 'bold' => true]);
        $run->addText('styled run', 'WorksEmphasis');
        $run->addTextBreak();
        $run->addText("next line\r\nlast line");
        $section->addTextBreak();
        $section->addText('Final paragraph');
        $output = $this->import($document);
        foreach (['insertText(text: Works API demo)', 'insertText(text: Bonjour — café )', 'insertText(text: styled run)', 'insertText(text: next line)', 'insertText(text: last line)', 'insertText(text: Final paragraph)', 'fo:font-size: 18.0000pt, fo:font-weight: bold', 'style:font-name: Courier New', 'fo:color: #c03010', 'fo:font-style: italic', 'fo:text-align: center', 'fo:text-align: end', 'fo:margin-top: 0.5000in', 'fo:margin-left: 0.7500in', 'style:print-orientation: landscape'] as $expected) {
            self::assertStringContainsString($expected, $output);
        }
        self::assertSame(2, substr_count($output, 'insertLineBreak()'));
        self::assertSame(4, substr_count($output, 'openParagraph('));
    }

    public function testIndependentImportAcrossFormattingAndIndexPages(): void
    {
        $document = new PhpWord();
        $section = $document->addSection();
        for ($i = 0; $i < 1200; ++$i) {
            $section->addText('Paragraph ' . $i . ' — café', ['bold' => $i % 2 === 0, 'size' => 10 + $i % 3], ['alignment' => ['left', 'right', 'center'][$i % 3]]);
        }
        $output = $this->import($document);
        self::assertSame(1200, substr_count($output, 'openParagraph('));
        self::assertSame(600, substr_count($output, 'fo:font-weight: bold'));
        for ($i = 0; $i < 1200; ++$i) {
            self::assertStringContainsString('insertText(text: Paragraph ' . $i . ' — café)', $output);
        }
    }

    public function testUnsupportedContentPreservesDestination(): void
    {
        $document = new PhpWord();
        $document->addSection()->addTable()->addRow()->addCell()->addText('Unsupported table');
        $filename = tempnam(sys_get_temp_dir(), 'wps-test-');
        file_put_contents($filename, 'Existing document');

        try {
            try {
                IOFactory::createWriter($document, 'WPS')->save($filename);
                self::fail('Unsupported content must be reported.');
            } catch (Exception $exception) {
                self::assertStringContainsString('Table', $exception->getMessage());
            }
            self::assertSame('Existing document', file_get_contents($filename));
        } finally {
            unlink($filename);
        }
    }

    public function testRepeatedSaveDoesNotDuplicateContents(): void
    {
        $document = new PhpWord();
        $document->addSection()->addText('Repeated save');
        $writer = IOFactory::createWriter($document, 'WPS');
        $filename = tempnam(sys_get_temp_dir(), 'wps-test-');

        try {
            $writer->save($filename);
            $first = file_get_contents($filename);
            $writer->save($filename);
            self::assertSame($first, file_get_contents($filename));
        } finally {
            unlink($filename);
        }
    }

    private function import(PhpWord $document): string
    {
        $binary = getenv('PHPWORD_WPS2RAW');
        if (!$binary) {
            self::markTestSkipped('Set PHPWORD_WPS2RAW to libwps wps2raw for independent import tests.');
        }
        $filename = tempnam(sys_get_temp_dir(), 'wps-test-');

        try {
            IOFactory::createWriter($document, 'WPS')->save($filename);
            $lines = [];
            $status = 0;
            exec(escapeshellarg($binary) . ' ' . escapeshellarg($filename) . ' 2>&1', $lines, $status);
            self::assertSame(0, $status, implode("\n", $lines));

            return implode("\n", $lines);
        } finally {
            unlink($filename);
        }
    }
}
