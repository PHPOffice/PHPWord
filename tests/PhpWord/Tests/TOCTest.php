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

        TOC::resetTitles();
        $this->assertEquals(0, count($toc->getTitles()));
    }
}
