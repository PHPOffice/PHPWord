<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\Object;

/**
 * Test class for PhpOffice\PhpWord\Section\Object
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Section\Object
 * @runTestsInSeparateProcesses
 */
class ObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructWithSupportedFiles()
    {
        $src = __DIR__ . "/../_files/documents/sheet.xls";
        $oObject = new Object($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Object', $oObject);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oObject->getStyle());
        $this->assertEquals($oObject->getSource(), $src);
    }

    public function testConstructWithNotSupportedFiles()
    {
        $src = __DIR__ . "/../_files/xsl/passthrough.xsl";
        $oObject = new Object($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Object', $oObject);
        $this->assertEquals($oObject->getSource(), null);
        $this->assertEquals($oObject->getStyle(), null);
    }

    public function testConstructWithSupportedFilesAndStyle()
    {
        $src = __DIR__ . "/../_files/documents/sheet.xls";
        $oObject = new Object($src, array('width' => '230px'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Object', $oObject);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oObject->getStyle());
        $this->assertEquals($oObject->getSource(), $src);
    }

    public function testRelationId()
    {
        $src = __DIR__ . "/../_files/documents/sheet.xls";
        $oObject = new Object($src);

        $iVal = rand(1, 1000);
        $oObject->setRelationId($iVal);
        $this->assertEquals($oObject->getRelationId(), $iVal);
    }

    public function testImageRelationId()
    {
        $src = __DIR__ . "/../_files/documents/sheet.xls";
        $oObject = new Object($src);

        $iVal = rand(1, 1000);
        $oObject->setImageRelationId($iVal);
        $this->assertEquals($oObject->getImageRelationId(), $iVal);
    }

    public function testObjectId()
    {
        $src = __DIR__ . "/../_files/documents/sheet.xls";
        $oObject = new Object($src);

        $iVal = rand(1, 1000);
        $oObject->setObjectId($iVal);
        $this->assertEquals($oObject->getObjectId(), $iVal);
    }
}
