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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\Font;

/**
 * Test class for PhpOffice\PhpWord\Element\Link
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Link
 * @runTestsInSeparateProcesses
 */
class LinkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create new instance
     */
    public function testConstructDefault()
    {
        $oLink = new Link('https://github.com/PHPOffice/PHPWord');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $oLink);
        $this->assertEquals('https://github.com/PHPOffice/PHPWord', $oLink->getSource());
        $this->assertEquals($oLink->getSource(), $oLink->getText());
        $this->assertNull($oLink->getFontStyle());
        $this->assertNull($oLink->getParagraphStyle());
    }

    /**
     * Create new instance with array
     */
    public function testConstructWithParamsArray()
    {
        $oLink = new Link(
            'https://github.com/PHPOffice/PHPWord',
            'PHPWord on GitHub',
            array('color' => '0000FF', 'underline' => Font::UNDERLINE_SINGLE),
            array('marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600)
        );

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $oLink);
        $this->assertEquals('https://github.com/PHPOffice/PHPWord', $oLink->getSource());
        $this->assertEquals('PHPWord on GitHub', $oLink->getText());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oLink->getFontStyle());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oLink->getParagraphStyle());
    }

    /**
     * Create new instance with style name string
     */
    public function testConstructWithParamsString()
    {
        $oLink = new Link('https://github.com/PHPOffice/PHPWord', null, 'fontStyle', 'paragraphStyle');

        $this->assertEquals('fontStyle', $oLink->getFontStyle());
        $this->assertEquals('paragraphStyle', $oLink->getParagraphStyle());
    }

    /**
     * Set/get relation Id
     */
    public function testRelationId()
    {
        $oLink = new Link('https://github.com/PHPOffice/PHPWord');

        $iVal = rand(1, 1000);
        $oLink->setRelationId($iVal);
        $this->assertEquals($iVal, $oLink->getRelationId());
    }
}
