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

use PhpOffice\PhpWord\Element\ListItemRun;
use PhpOffice\PhpWord\PhpWord;

/**
 * Test class for PhpOffice\PhpWord\Element\ListItemRun
 *
 * @runTestsInSeparateProcesses
 */
class ListItemRunTest extends \PHPUnit_Framework_TestCase
{
    /**
     * New instance
     */
    public function testConstructNull()
    {
        $oListItemRun = new ListItemRun();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\ListItemRun', $oListItemRun);
        $this->assertCount(0, $oListItemRun->getElements());
        $this->assertEquals($oListItemRun->getParagraphStyle(), null);
    }

    /**
     * New instance with string
     */
    public function testConstructString()
    {
        $oListItemRun = new ListItemRun(0, null, null, 'pStyle');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\ListItemRun', $oListItemRun);
        $this->assertCount(0, $oListItemRun->getElements());
        $this->assertEquals($oListItemRun->getParagraphStyle(), 'pStyle');
    }

    /**
     * New instance with array
     */
    public function testConstructArray()
    {
        $oListItemRun = new ListItemRun(0, null, null, array('spacing' => 100));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\ListItemRun', $oListItemRun);
        $this->assertCount(0, $oListItemRun->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oListItemRun->getParagraphStyle());
    }
    
    /**
     * Get style
     */
    public function testStyle()
    {
        $oListItemRun = new ListItemRun(
            1,
            null,
            array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER)
        );
    
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\ListItem', $oListItemRun->getStyle());
        $this->assertEquals(
            $oListItemRun->getStyle()->getListType(),
            \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER
        );
    }
    /**
     * getDepth
     */
    public function testDepth()
    {
        $iVal = rand(1, 1000);
        $oListItemRun = new ListItemRun($iVal);
    
        $this->assertEquals($oListItemRun->getDepth(), $iVal);
    }
    
    /**
     * Add text
     */
    public function testAddText()
    {
        $oListItemRun = new ListItemRun();
        $element = $oListItemRun->addText('text');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        $this->assertCount(1, $oListItemRun->getElements());
        $this->assertEquals($element->getText(), 'text');
    }

    /**
     * Add text non-UTF8
     */
    public function testAddTextNotUTF8()
    {
        $oListItemRun = new ListItemRun();
        $element = $oListItemRun->addText(utf8_decode('ééé'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        $this->assertCount(1, $oListItemRun->getElements());
        $this->assertEquals($element->getText(), 'ééé');
    }

    /**
     * Add link
     */
    public function testAddLink()
    {
        $oListItemRun = new ListItemRun();
        $element = $oListItemRun->addLink('http://www.google.fr');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
        $this->assertCount(1, $oListItemRun->getElements());
        $this->assertEquals($element->getTarget(), 'http://www.google.fr');
    }

    /**
     * Add link with name
     */
    public function testAddLinkWithName()
    {
        $oListItemRun = new ListItemRun();
        $element = $oListItemRun->addLink('http://www.google.fr', utf8_decode('ééé'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
        $this->assertCount(1, $oListItemRun->getElements());
        $this->assertEquals($element->getTarget(), 'http://www.google.fr');
        $this->assertEquals($element->getText(), 'ééé');
    }

    /**
     * Add text break
     */
    public function testAddTextBreak()
    {
        $oListItemRun = new ListItemRun();
        $oListItemRun->addTextBreak(2);

        $this->assertCount(2, $oListItemRun->getElements());
    }

    /**
     * Add image
     */
    public function testAddImage()
    {
        $src = __DIR__ . "/../_files/images/earth.jpg";

        $oListItemRun = new ListItemRun();
        $element = $oListItemRun->addImage($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
        $this->assertCount(1, $oListItemRun->getElements());
    }
}
