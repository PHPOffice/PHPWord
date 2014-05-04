<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Tab;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Style\Tab
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Tab
 * @runTestsInSeparateProcesses
 */
class TabsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test if the tabs has been created properly
     */
    public function testTabsStyle()
    {
        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('tabbed', array('tabs' => array(new Tab('left', 1440, 'dot'))));
        $doc = TestHelperDOCX::getDocument($phpWord);
        $file = 'word/styles.xml';
        $path = '/w:styles/w:style[@w:styleId="tabbed"]/w:pPr/w:tabs/w:tab[1]';
        $element = $doc->getElement($path, $file);
        $this->assertEquals('left', $element->getAttribute('w:val'));
        $this->assertEquals(1440, $element->getAttribute('w:pos'));
        $this->assertEquals('dot', $element->getAttribute('w:leader'));
    }
}
