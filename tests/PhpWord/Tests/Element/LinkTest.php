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

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\Link;
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
        $oLink = new Link('http://www.google.com');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $oLink);
        $this->assertEquals($oLink->getSource(), 'http://www.google.com');
        $this->assertEquals($oLink->getText(), $oLink->getSource());
        $this->assertEquals($oLink->getFontStyle(), null);
        $this->assertEquals($oLink->getParagraphStyle(), null);
    }

    /**
     * Create new instance with array
     */
    public function testConstructWithParamsArray()
    {
        $oLink = new Link(
            'http://www.google.com',
            'Search Engine',
            array('color' => '0000FF', 'underline' => Font::UNDERLINE_SINGLE),
            array('marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600)
        );

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $oLink);
        $this->assertEquals($oLink->getSource(), 'http://www.google.com');
        $this->assertEquals($oLink->getText(), 'Search Engine');
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oLink->getFontStyle());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oLink->getParagraphStyle());
    }

    /**
     * Create new instance with style name string
     */
    public function testConstructWithParamsString()
    {
        $oLink = new Link('http://www.google.com', null, 'fontStyle', 'paragraphStyle');

        $this->assertEquals($oLink->getFontStyle(), 'fontStyle');
        $this->assertEquals($oLink->getParagraphStyle(), 'paragraphStyle');
    }

    /**
     * Set/get relation Id
     */
    public function testRelationId()
    {
        $oLink = new Link('http://www.google.com');

        $iVal = rand(1, 1000);
        $oLink->setRelationId($iVal);
        $this->assertEquals($oLink->getRelationId(), $iVal);
    }
}
