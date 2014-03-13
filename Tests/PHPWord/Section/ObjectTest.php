<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_Object;

class ObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructWithSupportedFiles()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $oObject = new PHPWord_Section_Object($src);

        $this->assertInstanceOf('PHPWord_Section_Object', $oObject);
        $this->assertInstanceOf('PHPWord_Style_Image', $oObject->getStyle());
        $this->assertEquals($oObject->getSource(), $src);
    }

    public function testConstructWithNotSupportedFiles()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'xsl', 'passthrough.xsl')
        );
        $oObject = new PHPWord_Section_Object($src);

        $this->assertInstanceOf('PHPWord_Section_Object', $oObject);
        $this->assertEquals($oObject->getSource(), null);
        $this->assertEquals($oObject->getStyle(), null);
    }

    public function testConstructWithSupportedFilesAndStyle()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $oObject = new PHPWord_Section_Object($src, array('width' => '230px'));

        $this->assertInstanceOf('PHPWord_Section_Object', $oObject);
        $this->assertInstanceOf('PHPWord_Style_Image', $oObject->getStyle());
        $this->assertEquals($oObject->getSource(), $src);
    }

    public function testRelationId()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $oObject = new PHPWord_Section_Object($src);

        $iVal = rand(1, 1000);
        $oObject->setRelationId($iVal);
        $this->assertEquals($oObject->getRelationId(), $iVal);
    }

    public function testImageRelationId()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $oObject = new PHPWord_Section_Object($src);

        $iVal = rand(1, 1000);
        $oObject->setImageRelationId($iVal);
        $this->assertEquals($oObject->getImageRelationId(), $iVal);
    }

    public function testObjectId()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $oObject = new PHPWord_Section_Object($src);

        $iVal = rand(1, 1000);
        $oObject->setObjectId($iVal);
        $this->assertEquals($oObject->getObjectId(), $iVal);
    }
}
