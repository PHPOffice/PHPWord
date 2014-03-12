<?php
namespace PHPWord\Tests\Style;

use PHPWord;
use PHPWord_Style_Tab;
use PHPWord_Style_Tabs;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class TabsTest
 *
 * @package PHPWord\Tests
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
        $PHPWord = new PHPWord();
        $PHPWord->addParagraphStyle('tabbed', array(
            'tabs' => array(
                new PHPWord_Style_Tab('left', 1440, 'dot'),
            )
        ));
        $doc = TestHelperDOCX::getDocument($PHPWord);
        $file = 'word/styles.xml';
        $path = '/w:styles/w:style[@w:styleId="tabbed"]/w:pPr/w:tabs/w:tab[1]';
        $element = $doc->getElement($path, $file);
        $this->assertEquals('left', $element->getAttribute('w:val'));
        $this->assertEquals(1440, $element->getAttribute('w:pos'));
        $this->assertEquals('dot', $element->getAttribute('w:leader'));
    }
}
