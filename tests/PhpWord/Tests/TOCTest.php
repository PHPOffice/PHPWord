<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\TOC;

/**
 * Test class for PhpOffice\PhpWord\TOC
 *
 * @runTestsInSeparateProcesses
 */
class TOCTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Construct with font and TOC style in array format
     */
    public function testConstructWithStyleArray()
    {
        $expected = array(
            'tabPos'    => 9062,
            'tabLeader' => \PhpOffice\PhpWord\Style\TOC::TABLEADER_DOT,
            'indent'    => 200,
        );
        $object = new TOC(array('size' => 11), array('tabPos' => $expected['tabPos']));
        $tocStyle = $object->getStyleTOC();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\TOC', $tocStyle);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $object->getStyleFont());

        foreach ($expected as $key => $value) {
            $method = "get{$key}";
            $this->assertEquals($value, $tocStyle->$method());
        }
    }

    /**
     * Construct with named font style
     */
    public function testConstructWithStyleName()
    {
        $object = new TOC('Font Style');
        $tocStyle = $object->getStyleTOC();

        $this->assertEquals('Font Style', $object->getStyleFont());
    }

    /**
     * Add and get title
     */
    public function testAddAndGetTitle()
    {
        $titleCount = 3;
        $anchor = '_Toc' . (252634154 + $titleCount);
        $bookmark = $titleCount - 1;
        $titles = array(
            'Heading 1' => 1,
            'Heading 2' => 2,
            'Heading 3' => 3,
        );
        $toc = new TOC();

        foreach ($titles as $text => $depth) {
            $response = $toc->addTitle($text, $depth);
        }
        $this->assertEquals($anchor, $response[0]);
        $this->assertEquals($bookmark, $response[1]);

        $i = 0;
        $savedTitles = $toc->getTitles();
        foreach ($titles as $text => $depth) {
            $this->assertEquals($text, $savedTitles[$i]['text']);
            $this->assertEquals($depth, $savedTitles[$i]['depth']);
            $i++;
        }
    }

    /**
     * Set/get minDepth and maxDepth
     */
    public function testSetGetMinMaxDepth()
    {
        $toc = new TOC();
        $titles = array(
            'Heading 1' => 1,
            'Heading 2' => 2,
            'Heading 3' => 3,
            'Heading 4' => 4,
        );
        foreach ($titles as $text => $depth) {
            $toc->addTitle($text, $depth);
        }

        $this->assertEquals(1, $toc->getMinDepth());
        $this->assertEquals(9, $toc->getMaxDepth());

        $toc->setMinDepth(2);
        $toc->setMaxDepth(3);
        $toc->getTitles();

        $this->assertEquals(2, $toc->getMinDepth());
        $this->assertEquals(3, $toc->getMaxDepth());
    }
}
