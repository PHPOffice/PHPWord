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

use DOMDocument;
use Exception;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Throwable;
use TypeError;
use ZipArchive;

/**
 * @covers \PhpOffice\PhpWord\TemplateProcessor
 *
 * @coversDefaultClass \PhpOffice\PhpWord\TemplateProcessor
 *
 * @runTestsInSeparateProcesses
 */
final class TemplateProcessorTest extends \PHPUnit\Framework\TestCase
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

    /**
     * Construct test.
     *
     * @covers ::__construct
     * @covers ::__destruct
     * @covers \PhpOffice\PhpWord\Shared\ZipArchive::close
     */
    public function testTheConstruct(): void
    {
        $object = $this->getTemplateProcessor(__DIR__ . '/_files/templates/blank.docx');
        self::assertEquals([], $object->getVariables());
        $object->save();

        try {
            $object->zip()->close();
            self::fail('Expected exception for double close');
        } catch (Throwable $e) {
            // nothing to do here
        }
    }

    /**
     * Test macroExists method.
     *
     * @covers ::macroExists
     */
    public function testMacroExists(): void
    {
        $templateProcessor = $this->getTemplateProcessor(__DIR__ . '/_files/templates/setValue.docx');
        self::assertTrue($templateProcessor->macroExists('Value1'));
        self::assertTrue($templateProcessor->macroExists('${Value1}'));
        self::assertFalse($templateProcessor->macroExists('nonExistentMacro'));
    }
}
