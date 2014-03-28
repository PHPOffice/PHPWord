<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Footnote;

/**
 * Test class for PhpOffice\PhpWord\Footnote
 *
 * @runTestsInSeparateProcesses
 */
class FootnoteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test add, get, and count footnote elements and links
     */
    public function testFootnote()
    {
        $footnoteElement = new \PhpOffice\PhpWord\Section\Footnote();
        $rIdFootnote = Footnote::addFootnoteElement($footnoteElement);
        $rIdLink = Footnote::addFootnoteLinkElement('http://test.com');

        $this->assertEquals(2, $rIdFootnote);
        $this->assertEquals(1, $rIdLink);
        $this->assertEquals(1, count(Footnote::getFootnoteElements()));
        $this->assertEquals(1, count(Footnote::getFootnoteLinkElements()));
    }
}
