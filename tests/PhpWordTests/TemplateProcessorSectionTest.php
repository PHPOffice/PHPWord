<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests;

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * @covers \PhpOffice\PhpWord\TemplateProcessor
 *
 * @coversDefaultClass \PhpOffice\PhpWord\TemplateProcessor
 *
 * @runTestsInSeparateProcesses
 */
final class TemplateProcessorSectionTest extends \PHPUnit\Framework\TestCase
{
    /** @var ?TemplateProcessor */
    private $templateProcessor;

    private function getTemplateProcessor(string $filename): TemplateProcessor
    {
        $this->templateProcessor = new TemplateProcessor($filename);

        return $this->templateProcessor;
    }

    protected function tearDown(): void
    {
        if ($this->templateProcessor !== null) {
            $filename = $this->templateProcessor->getTempDocumentFilename();
            $this->templateProcessor = null;
            if (file_exists($filename)) {
                @unlink($filename);
            }
        }
    }

    public function testSetComplexSection(): void
    {
        $templateProcessor = $this->getTemplateProcessor(__DIR__ . '/_files/templates/document22-xml.docx');
        $html = '
            <p>&nbsp;Bug Report:</p>
            <p><span style="background-color: #ff0000;">BugTracker X</span> is ${facing1} an issue.</p>
            <p><span style="background-color: #00ff00;">BugTracker X</span> is ${facing2} an issue.</p>
            <p><span style="background-color: #0000ff;">BugTracker X</span> is ${facing1} an issue.</p>
            ';
        $section = new Section(0);
        Html::addHtml($section, $html, false, false);
        $templateProcessor->setComplexBlock('test', $section);
        $facing1 = new TextRun();
        $facing1->addText('facing', ['bold' => true]);
        $facing2 = new TextRun();
        $facing2->addText('facing', ['italic' => true]);

        $templateProcessor->setComplexBlock('test', $section);
        $templateProcessor->setComplexValue('facing1', $facing1, true);
        $templateProcessor->setComplexValue('facing2', $facing2);

        $docName = $templateProcessor->save();
        $docFound = file_exists($docName);
        self::assertTrue($docFound);
        $contents = file_get_contents("zip://$docName#word/document2.xml");
        unlink($docName);
        self::assertNotFalse($contents);
        $contents = preg_replace('/>\s+</', '><', $contents) ?? '';
        self::assertStringContainsString('<w:t>Test</w:t>', $contents);
        $count = substr_count($contents, '<w:r><w:rPr><w:b w:val="1"/><w:bCs w:val="1"/></w:rPr><w:t xml:space="preserve">facing</w:t></w:r>');
        self::assertSame(2, $count, 'should be 2 bold strings');
        $count = substr_count($contents, '<w:r><w:rPr><w:i w:val="1"/><w:iCs w:val="1"/></w:rPr><w:t xml:space="preserve">facing</w:t></w:r>');
        self::assertSame(1, $count, 'should be 1 italic string');
        self::assertStringNotContainsString('$', $contents, 'no leftover macros');
        self::assertStringNotContainsString('facing1', $contents, 'no leftover replaced string1');
        self::assertStringNotContainsString('facing2', $contents, 'no leftover replaced string2');
    }
}
