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

namespace PhpOffice\PhpWordTests\Reader\ODText;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;

class ODTextSectionTest extends \PHPUnit\Framework\TestCase
{
    /** @var string */
    private $filename = '';

    protected function tearDown(): void
    {
        if ($this->filename !== '') {
            unlink($this->filename);
            $this->filename = '';
        }
    }

    public function testWriteThenReadSection(): void
    {
        $dir = 'tests/PhpWordTests/_files';
        Settings::setOutputEscapingEnabled(true);
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $inputText = ['days', 'monday', 'tuesday'];
        $inputText[] = "Tab\tthen two spaces  then done.";
        foreach ($inputText as $text) {
            $section->addText($text);
        }
        $writer = IOFactory::createWriter($phpWord, 'ODText');
        $this->filename = "$dir/sectiontest.odt";
        $writer->save($this->filename);

        $reader = IOFactory::createReader('ODText');
        $phpWord2 = $reader->load($this->filename);
        $outputText = [];
        foreach ($phpWord2->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (is_object($element) && method_exists($element, 'getText')) {
                    $outputText[] = $element->getText();
                }
            }
        }
        self::assertSame($inputText, $outputText);
    }

    public function testReadNoSections(): void
    {
        $dir = 'tests/PhpWordTests/_files/documents';
        $inputText = ['days', 'monday', 'tuesday'];

        $reader = IOFactory::createReader('ODText');
        $filename = "$dir/word.2493.nosection.odt";
        $phpWord2 = $reader->load($filename);
        $outputText = [];
        foreach ($phpWord2->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (is_object($element) && method_exists($element, 'getText')) {
                    $outputText[] = $element->getText();
                }
            }
        }
        self::assertSame($inputText, $outputText);
    }
}
