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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Tab;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Style\Paragraph
 *
 * @runTestsInSeparateProcesses
 */
class ParagraphTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tear down after each test
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test setting style values with null or empty value
     */
    public function testSetStyleValueWithNullOrEmpty()
    {
        $object = new Paragraph();

        $attributes = array(
            'widowControl' => true,
            'keepNext' => false,
            'keepLines' => false,
            'pageBreakBefore' => false,
        );
        foreach ($attributes as $key => $default) {
            $get = "get{$key}";
            $object->setStyleValue("$key", null);
            $this->assertEquals($default, $object->$get());
            $object->setStyleValue("$key", '');
            $this->assertEquals($default, $object->$get());
        }
    }

    /**
     * Test setting style values with normal value
     */
    public function testSetStyleValueNormal()
    {
        $object = new Paragraph();

        $attributes = array(
            'align' => 'justify',
            'spaceAfter' => 240,
            'spaceBefore' => 240,
            'indent' => 1,
            'hanging' => 1,
            'spacing' => 120,
            'basedOn' => 'Normal',
            'next' => 'Normal',
            'numStyle' => 'numStyle',
            'numLevel' => 1,
            'widowControl' => false,
            'keepNext' => true,
            'keepLines' => true,
            'pageBreakBefore' => true,
        );
        foreach ($attributes as $key => $value) {
            $get = "get{$key}";
            $object->setStyleValue("$key", $value);
            if ($key == 'align') {
                if ($value == 'justify') {
                    $value = 'both';
                }
            } elseif ($key == 'indent' || $key == 'hanging') {
                $value = $value * 720;
            } elseif ($key == 'spacing') {
                $value += 240;
            }
            $this->assertEquals($value, $object->$get());
        }
    }

    /**
     * Test get null style value
     */
    public function testGetNullStyleValue()
    {
        $object = new Paragraph();

        $attributes = array('spacing', 'indent', 'hanging', 'spaceBefore', 'spaceAfter');
        foreach ($attributes as $key) {
            $get = "get{$key}";
            $this->assertNull($object->$get());
        }
    }

    /**
     * Test tabs
     */
    public function testTabs()
    {
        $object = new Paragraph();
        $object->setTabs(array(new Tab('left', 1550), new Tab('right', 5300)));
        $this->assertCount(2, $object->getTabs());
    }

    /**
     * Line height
     */
    public function testLineHeight()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Test style array
        $text = $section->addText('This is a test', array(), array(
            'line-height' => 2.0
        ));

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(480, $lineHeight);
        $this->assertEquals('auto', $lineRule);

        // Test setter
        $text->getParagraphStyle()->setLineHeight(3.0);
        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(720, $lineHeight);
        $this->assertEquals('auto', $lineRule);
    }

    /**
     * Test setLineHeight validation
     */
    public function testLineHeightValidation()
    {
        $object = new Paragraph();
        $object->setLineHeight('12.5pt');
        $this->assertEquals(12.5, $object->getLineHeight());
    }

    /**
     * Test line height exception by using nonnumeric value
     *
     * @expectedException \PhpOffice\PhpWord\Exception\InvalidStyleException
     */
    public function testLineHeightException()
    {
        $object = new Paragraph();
        $object->setLineHeight('a');
    }
}
