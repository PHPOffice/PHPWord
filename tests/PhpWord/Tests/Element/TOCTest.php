<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\TOC;
use PhpOffice\PhpWord\PhpWord;

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
            'position'    => 9062,
            'leader' => \PhpOffice\PhpWord\Style\Tab::TAB_LEADER_DOT,
            'indent'    => 200,
        );
        $object = new TOC(array('size' => 11), array('position' => $expected['position']));
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
        // $tocStyle = $object->getStyleTOC();

        $this->assertEquals('Font Style', $object->getStyleFont());
    }

    /**
     * Set/get minDepth and maxDepth
     */
    public function testSetGetMinMaxDepth()
    {
        $titles = array(
            'Heading 1' => 1,
            'Heading 2' => 2,
            'Heading 3' => 3,
            'Heading 4' => 4,
        );

        $phpWord = new PhpWord();
        foreach ($titles as $text => $depth) {
            $phpWord->addTitle(new Title($text, $depth));
        }
        $toc = new TOC();
        $toc->setPhpWord($phpWord);
        $this->assertEquals(1, $toc->getMinDepth());
        $this->assertEquals(9, $toc->getMaxDepth());

        $toc->setMinDepth(2);
        $toc->setMaxDepth(3);
        $toc->getTitles();

        $this->assertEquals(2, $toc->getMinDepth());
        $this->assertEquals(3, $toc->getMaxDepth());
    }
}
