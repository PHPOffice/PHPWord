<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\TOC;

/**
 * Test class for PhpOffice\PhpWord\Element\TOC
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
        $object = new TOC(array('_size' => 11), array('_tabPos' => $expected['tabPos']));
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
            \PhpOffice\PhpWord\TOC::addTitle($text, $depth);
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
