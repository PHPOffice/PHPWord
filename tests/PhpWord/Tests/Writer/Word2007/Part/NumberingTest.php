<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer\Word2007\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Numbering as NumberingStyle;
use PhpOffice\PhpWord\Style\NumberingLevel;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;
use PhpOffice\PhpWord\Writer\Word2007\Part\Numbering;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Numbering
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Part\Numbering
 * @runTestsInSeparateProcesses
 * @since 0.10.0
 */
class NumberingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Write footnotes
     */
    public function testWriteNumbering()
    {
        $xmlFile = 'word/numbering.xml';

        $phpWord = new PhpWord();
        $phpWord->addNumberingStyle(
            'numStyle',
            array(
                'type' => 'multilevel',
                'levels' => array(
                    array(
                        'start' => 1,
                        'format' => 'decimal',
                        'restart' => 1,
                        'suffix' => 'space',
                        'text' => '%1.',
                        'align' => 'left',
                        'left' => 360,
                        'hanging' => 360,
                        'tabPos' => 360,
                        'font' => 'Arial',
                        'hint' => 'default',
                    ),
                )
            )
        );

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $this->assertTrue($doc->elementExists('/w:numbering/w:abstractNum', $xmlFile));
    }
}
