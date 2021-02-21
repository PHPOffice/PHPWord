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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Style\Image;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Font
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Frame
 * @runTestsInSeparateProcesses
 */
class ImageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test writing image wrapping
     */
    public function testWrapping()
    {
        $styles = array(
            'wrap'               => Image::WRAP_INLINE,
            'wrapDistanceLeft'   => 10,
            'wrapDistanceRight'  => 20,
            'wrapDistanceTop'    => 30,
            'wrapDistanceBottom' => 40,
        );

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addImage(__DIR__ . '/../../../_files/images/earth.jpg', $styles);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:p[1]/w:r/w:rPr/w:position';
        $this->assertFalse($doc->elementExists($path));
        $path = '/w:document/w:body/w:p[1]/w:r/w:pict/v:shape';
        $this->assertTrue($doc->elementExists($path . '/w10:wrap'));
        $this->assertEquals('inline', $doc->getElementAttribute($path . '/w10:wrap', 'type'));

        $this->assertTrue($doc->elementExists($path));
        $style = $doc->getElement($path)->getAttribute('style');
        $this->assertNotNull($style);
        $this->assertContains('mso-wrap-distance-left:10pt;', $style);
        $this->assertContains('mso-wrap-distance-right:20pt;', $style);
        $this->assertContains('mso-wrap-distance-top:30pt;', $style);
        $this->assertContains('mso-wrap-distance-bottom:40pt;', $style);
    }

    /**
     * Test writing image wrapping
     */
    public function testWrappingWithPosition()
    {
        $styles = array(
            'wrap'               => Image::WRAP_INLINE,
            'wrapDistanceLeft'   => 10,
            'wrapDistanceRight'  => 20,
            'wrapDistanceTop'    => 30,
            'wrapDistanceBottom' => 40,
            'position'           => 10,
        );

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addImage(__DIR__ . '/../../../_files/images/earth.jpg', $styles);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:p[1]/w:r/w:rPr/w:position';
        $this->assertEquals('10', $doc->getElement($path)->getAttribute('w:val'));
        $path = '/w:document/w:body/w:p[1]/w:r/w:pict/v:shape';
        $this->assertTrue($doc->elementExists($path . '/w10:wrap'));
        $this->assertEquals('inline', $doc->getElementAttribute($path . '/w10:wrap', 'type'));

        $this->assertTrue($doc->elementExists($path));
        $style = $doc->getElement($path)->getAttribute('style');
        $this->assertNotNull($style);
        $this->assertContains('mso-wrap-distance-left:10pt;', $style);
        $this->assertContains('mso-wrap-distance-right:20pt;', $style);
        $this->assertContains('mso-wrap-distance-top:30pt;', $style);
        $this->assertContains('mso-wrap-distance-bottom:40pt;', $style);
    }
}
