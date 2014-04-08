<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\PreserveText;

/**
 * Test class for PhpOffice\PhpWord\Element\PreserveText
 *
 * @runTestsInSeparateProcesses
 */
class PreserveTextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create new instance
     */
    public function testConstruct()
    {
        $oPreserveText = new PreserveText();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $oPreserveText);
        $this->assertEquals($oPreserveText->getText(), null);
        $this->assertEquals($oPreserveText->getFontStyle(), null);
        $this->assertEquals($oPreserveText->getParagraphStyle(), null);
    }

    /**
     * Create new instance with style name
     */
    public function testConstructWithString()
    {
        $oPreserveText = new PreserveText('text', 'styleFont', 'styleParagraph');
        $this->assertEquals($oPreserveText->getText(), array('text'));
        $this->assertEquals($oPreserveText->getFontStyle(), 'styleFont');
        $this->assertEquals($oPreserveText->getParagraphStyle(), 'styleParagraph');
    }

    /**
     * Create new instance with array
     */
    public function testConstructWithArray()
    {
        $oPreserveText = new PreserveText(
            'text',
            array('align' => 'center'),
            array('marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600)
        );
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oPreserveText->getFontStyle());
        $this->assertInstanceOf(
            'PhpOffice\\PhpWord\\Style\\Paragraph',
            $oPreserveText->getParagraphStyle()
        );
    }
}
