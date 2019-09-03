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

namespace PhpOffice\PhpWord\Style\Colors;

/**
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Colors\SystemColor
 */
class SystemColorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Provided system color must be a valid system color. 'FakeColor' provided. Allowed:
     */
    public function testConversions()
    {
        // Prepare test values [ original, expected ]
        $values = array(
            'scrollBar',
            'background',
            'activeCaption',
            'inactiveCaption',
            'menu',
            'window',
            'windowFrame',
            'menuText',
            'windowText',
            'captionText',
            'activeBorder',
            'inactiveBorder',
            'appWorkspace',
            'highlight',
            'highlightText',
            'btnFace',
            'btnShadow',
            'grayText',
            'btnText',
            'inactiveCaptionText',
            'btnHighlight',
            '3dDkShadow',
            '3dLight',
            'infoText',
            'infoBk',
            'hotLight',
            'gradientActiveCaption',
            'gradientInactiveCaption',
            'menuHighlight',
            'menuBar',

            'FakeColor',
        );
        // Conduct test
        foreach ($values as $value) {
            $message = $value . ' should be a valid color';
            $result = new SystemColor($value, new Hex('000'));
            $this->assertEquals($value, $result->getName(), $message);
            $this->assertEquals($value, $result->toHexOrName(), $message);
            $this->assertTrue($result->isSpecified(), $message);

            // Last color
            $this->assertEquals('#000000', $result->getLastColor()->toHex(true));
            $this->assertEquals('000000', $result->getLastColor()->toHex());
            $this->assertEquals(array(0, 0, 0), $result->getLastColor()->toRgb());
        }
    }
}
