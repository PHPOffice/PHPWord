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

use PhpOffice\PhpWord\Element\Link;
use PhpOffice\PhpWord\Style\Font;

/**
 * Test class for PhpOffice\PhpWord\Element\Link.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Link
 *
 * @runTestsInSeparateProcesses
 */
class LinkTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create new instance.
     */
    public function testConstructDefault(): void
    {
        $oLink = new Link('https://github.com/PHPOffice/PHPWord');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $oLink);
        self::assertEquals('https://github.com/PHPOffice/PHPWord', $oLink->getSource());
        self::assertEquals($oLink->getSource(), $oLink->getText());
        self::assertNull($oLink->getFontStyle());
        self::assertNull($oLink->getParagraphStyle());
    }

    /**
     * Create new instance with array.
     */
    public function testConstructWithParamsArray(): void
    {
        $oLink = new Link(
            'https://github.com/PHPOffice/PHPWord',
            'PHPWord on GitHub',
            ['color' => '0000FF', 'underline' => Font::UNDERLINE_SINGLE],
            ['marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600]
        );

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $oLink);
        self::assertEquals('https://github.com/PHPOffice/PHPWord', $oLink->getSource());
        self::assertEquals('PHPWord on GitHub', $oLink->getText());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oLink->getFontStyle());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oLink->getParagraphStyle());
    }

    /**
     * Create new instance with style name string.
     */
    public function testConstructWithParamsString(): void
    {
        $oLink = new Link('https://github.com/PHPOffice/PHPWord', null, 'fontStyle', 'paragraphStyle');

        self::assertEquals('fontStyle', $oLink->getFontStyle());
        self::assertEquals('paragraphStyle', $oLink->getParagraphStyle());
    }

    /**
     * Set/get relation Id.
     */
    public function testRelationId(): void
    {
        $oLink = new Link('https://github.com/PHPOffice/PHPWord');

        $iVal = mt_rand(1, 1000);
        $oLink->setRelationId($iVal);
        self::assertEquals($iVal, $oLink->getRelationId());
    }
}
