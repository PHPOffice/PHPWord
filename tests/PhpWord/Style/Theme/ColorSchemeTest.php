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

use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Colors\SystemColor;

/**
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Theme\ColorScheme
 */
class ColorSchemeTest extends \PHPUnit\Framework\TestCase
{
    public function testInitialization()
    {
        $colors = array(
            'dk1'      => new SystemColor('windowText', new Hex('000')),
            'dk2'      => new Hex('1F497D'),
            'lt1'      => new SystemColor('window', new Hex('fff')),
            'lt2'      => new Hex('EEECE1'),
            'accent1'  => new Hex('4F81BD'),
            'accent2'  => new Hex('C0504D'),
            'accent3'  => new Hex('9BBB59'),
            'accent4'  => new Hex('8064A2'),
            'accent5'  => new Hex('4BACC6'),
            'accent6'  => new Hex('F79646'),
            'hlink'    => new Hex('0000FF'),
            'folHlink' => new Hex('800080'),
        );
        $colorScheme = new ColorScheme($colors);

        foreach ($colors as $name => $color) {
            $this->assertInstanceOf(get_class($color), $colorScheme->getColor($name));
            $this->assertEquals($color->toHexOrName(), $colorScheme->getColor($name)->toHexOrName());
            $this->assertEquals($color->toHexOrName(), $colorScheme->getColors()[$name]->toHexOrName());
        }
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage 12 colors expected, but 1 colors provided
     */
    public function testTooFewColors()
    {
        new ColorScheme(array(
            'dk1' => new SystemColor('windowText', new Hex('000')),
        ));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage 12 colors expected, but 13 colors provided
     */
    public function testTooManyColors()
    {
        new ColorScheme(array(
            'dk1'      => new SystemColor('windowText', new Hex('000')),
            'dk2'      => new Hex('1F497D'),
            'dk3'      => new Hex('3D5CA3'),
            'lt1'      => new SystemColor('window', new Hex('fff')),
            'lt2'      => new Hex('EEECE1'),
            'accent1'  => new Hex('4F81BD'),
            'accent2'  => new Hex('C0504D'),
            'accent3'  => new Hex('9BBB59'),
            'accent4'  => new Hex('8064A2'),
            'accent5'  => new Hex('4BACC6'),
            'accent6'  => new Hex('F79646'),
            'hlink'    => new Hex('0000FF'),
            'folHlink' => new Hex('800080'),
        ));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Missing 'dk2' from provided color scheme
     */
    public function testWrongName()
    {
        new ColorScheme(array(
            'dk1'      => new SystemColor('windowText', new Hex('000')),
            'dk3'      => new Hex('1F497D'),
            'lt1'      => new SystemColor('window', new Hex('fff')),
            'lt2'      => new Hex('EEECE1'),
            'accent1'  => new Hex('4F81BD'),
            'accent2'  => new Hex('C0504D'),
            'accent3'  => new Hex('9BBB59'),
            'accent4'  => new Hex('8064A2'),
            'accent5'  => new Hex('4BACC6'),
            'accent6'  => new Hex('F79646'),
            'hlink'    => new Hex('0000FF'),
            'folHlink' => new Hex('800080'),
        ));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Provided color for 'dk1' must be an instance of 'PhpOffice\PhpWord\Style\Colors\SpecialColor', 'string' provided
     */
    public function testSettingBadColor()
    {
        new ColorScheme(array(
            'dk1'      => '000000',
            'dk2'      => new Hex('1F497D'),
            'lt1'      => new SystemColor('window', new Hex('fff')),
            'lt2'      => new Hex('EEECE1'),
            'accent1'  => new Hex('4F81BD'),
            'accent2'  => new Hex('C0504D'),
            'accent3'  => new Hex('9BBB59'),
            'accent4'  => new Hex('8064A2'),
            'accent5'  => new Hex('4BACC6'),
            'accent6'  => new Hex('F79646'),
            'hlink'    => new Hex('0000FF'),
            'folHlink' => new Hex('800080'),
        ));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage No color exists for 'dk3'
     */
    public function testGettingBadColor()
    {
        $colorScheme = new ColorScheme(array(
            'dk1'      => new SystemColor('windowText', new Hex('000')),
            'dk2'      => new Hex('1F497D'),
            'lt1'      => new SystemColor('window', new Hex('fff')),
            'lt2'      => new Hex('EEECE1'),
            'accent1'  => new Hex('4F81BD'),
            'accent2'  => new Hex('C0504D'),
            'accent3'  => new Hex('9BBB59'),
            'accent4'  => new Hex('8064A2'),
            'accent5'  => new Hex('4BACC6'),
            'accent6'  => new Hex('F79646'),
            'hlink'    => new Hex('0000FF'),
            'folHlink' => new Hex('800080'),
        ));
        $colorScheme->getColor('dk3');
    }
}
