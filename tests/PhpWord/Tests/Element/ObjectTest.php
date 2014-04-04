<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\Object;

/**
 * Test class for PhpOffice\PhpWord\Element\Object
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Object
 * @runTestsInSeparateProcesses
 */
class ObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create new instance with supported files
     */
    public function testConstructWithSupportedFiles()
    {
        $src = __DIR__ . "/../_files/documents/sheet.xls";
        $oObject = new Object($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Object', $oObject);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oObject->getStyle());
        $this->assertEquals($oObject->getSource(), $src);
    }

    /**
     * Create new instance with non-supported files
     */
    public function testConstructWithNotSupportedFiles()
    {
        $src = __DIR__ . "/../_files/xsl/passthrough.xsl";
        $oObject = new Object($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Object', $oObject);
        $this->assertEquals($oObject->getSource(), null);
        $this->assertEquals($oObject->getStyle(), null);
    }

    /**
     * Create with style
     */
    public function testConstructWithSupportedFilesAndStyle()
    {
        $src = __DIR__ . "/../_files/documents/sheet.xls";
        $oObject = new Object($src, array('width' => '230px'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Object', $oObject);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oObject->getStyle());
        $this->assertEquals($oObject->getSource(), $src);
    }

    /**
     * Set/get relation Id
     */
    public function testRelationId()
    {
        $src = __DIR__ . "/../_files/documents/sheet.xls";
        $oObject = new Object($src);

        $iVal = rand(1, 1000);
        $oObject->setRelationId($iVal);
        $this->assertEquals($oObject->getRelationId(), $iVal);
    }

    /**
     * Set/get image relation Id
     */
    public function testImageRelationId()
    {
        $src = __DIR__ . "/../_files/documents/sheet.xls";
        $oObject = new Object($src);

        $iVal = rand(1, 1000);
        $oObject->setImageRelationId($iVal);
        $this->assertEquals($oObject->getImageRelationId(), $iVal);
    }
}
