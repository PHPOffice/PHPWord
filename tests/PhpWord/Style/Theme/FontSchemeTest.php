<?php
declare(strict_types=1);
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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style\Theme;

/**
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Theme\FontScheme
 */
class FontSchemeTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaults()
    {
        $fontScheme = new FontScheme();

        $this->assertEquals('Office', $fontScheme->getName());

        $this->assertInstanceOf(HeadingFonts::class, $fontScheme->getHeadingFonts());

        $this->assertInstanceOf(BodyFonts::class, $fontScheme->getBodyFonts());
    }

    public function testCustomName()
    {
        $fontScheme = new FontScheme('New Name');

        $this->assertEquals('New Name', $fontScheme->getName());
    }

    public function testCustomFonts()
    {
        $fontScheme = new FontScheme(
            'Custom Heading Fonts',
            new HeadingFonts(array('Latin' => 'Custom Font')),
            new BodyFonts(array('Latin' => 'Another Custom Font'))
        );
        $this->assertEquals('Custom Font', $fontScheme->getHeadingFonts()->getLatin());
        $this->assertEquals('Another Custom Font', $fontScheme->getBodyFonts()->getLatin());
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessage Argument 2 passed to PhpOffice\PhpWord\Style\Theme\FontScheme::__construct() must be an instance of PhpOffice\PhpWord\Style\Theme\HeadingFonts
     */
    public function testBodyAsHeading()
    {
        new FontScheme(
            'Body as Heading',
            new BodyFonts(),
            new BodyFonts()
        );
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessage Argument 3 passed to PhpOffice\PhpWord\Style\Theme\FontScheme::__construct() must be an instance of PhpOffice\PhpWord\Style\Theme\BodyFonts
     */
    public function testHeadingAsBody()
    {
        new FontScheme(
            'Heading as Body',
            new HeadingFonts(),
            new HeadingFonts()
        );
    }
}
