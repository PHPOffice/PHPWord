<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Element;

use PhpOffice\PhpWord\Element\OLEObject;

/**
 * Test class for PhpOffice\PhpWord\Element\OLEObject.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\OLEObject
 *
 * @runTestsInSeparateProcesses
 */
class ObjectTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create new instance with supported files, 4 character extention.
     */
    public function testConstructWithSupportedFiles(): void
    {
        $src = __DIR__ . '/../_files/documents/reader.docx';
        $oObject = new OLEObject($src);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\OLEObject', $oObject);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oObject->getStyle());
        self::assertEquals($src, $oObject->getSource());
    }

    /**
     * Create new instance with supported files.
     */
    public function testConstructWithSupportedFilesLong(): void
    {
        $src = __DIR__ . '/../_files/documents/sheet.xls';
        $oObject = new OLEObject($src);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\OLEObject', $oObject);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oObject->getStyle());
        self::assertEquals($src, $oObject->getSource());
    }

    /**
     * Create new instance with non-supported files.
     */
    public function testConstructWithNotSupportedFiles(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\InvalidObjectException::class);
        $src = __DIR__ . '/../_files/xsl/passthrough.xsl';
        $oObject = new OLEObject($src);
        $oObject->getSource();
    }

    /**
     * Create with style.
     */
    public function testConstructWithSupportedFilesAndStyle(): void
    {
        $src = __DIR__ . '/../_files/documents/sheet.xls';
        $oObject = new OLEObject($src, ['width' => '230px']);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\OLEObject', $oObject);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oObject->getStyle());
        self::assertEquals($src, $oObject->getSource());
    }

    /**
     * Set/get relation Id.
     */
    public function testRelationId(): void
    {
        $src = __DIR__ . '/../_files/documents/sheet.xls';
        $oObject = new OLEObject($src);

        $iVal = mt_rand(1, 1000);
        $oObject->setRelationId($iVal);
        self::assertEquals($iVal, $oObject->getRelationId());
    }

    /**
     * Set/get image relation Id.
     */
    public function testImageRelationId(): void
    {
        $src = __DIR__ . '/../_files/documents/sheet.xls';
        $oObject = new OLEObject($src);

        $iVal = mt_rand(1, 1000);
        $oObject->setImageRelationId($iVal);
        self::assertEquals($iVal, $oObject->getImageRelationId());
    }
}
