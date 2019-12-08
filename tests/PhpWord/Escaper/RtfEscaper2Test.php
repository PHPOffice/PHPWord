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

namespace PhpOffice\PhpWord\Escaper;

/**
 * Test class for PhpOffice\PhpWord\Escaper\RTF
 */
class RtfEscaper2Test extends \PHPUnit\Framework\TestCase
{
    const HEADER = '\\pard\\nowidctlpar {\\cf0\\f0 ';
    const TRAILER = '}\\par';

    public function escapestring($str)
    {
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $parentWriter = new \PhpOffice\PhpWord\Writer\RTF();
        $element = new \PhpOffice\PhpWord\Element\Text($str);
        $txt = new \PhpOffice\PhpWord\Writer\RTF\Element\Text($parentWriter, $element);
        $txt2 = trim($txt->write());

        return $txt2;
    }

    public function expect($str)
    {
        return self::HEADER . $str . self::TRAILER;
    }

    /**
     * Test special characters which require escaping
     */
    public function testSpecial()
    {
        $str = 'Special characters { open brace } close brace \\ backslash';
        $expect = $this->expect('Special characters \\{ open brace \\} close brace \\\\ backslash');
        $this->assertEquals($expect, $this->escapestring($str));
    }

    /**
     * Test accented character
     */
    public function testAccent()
    {
        $str = 'Voilà - string with accented char';
        $expect = $this->expect('Voil\\uc0{\\u224} - string with accented char');
        $this->assertEquals($expect, $this->escapestring($str));
    }

    /**
     * Test Hebrew
     */
    public function testHebrew()
    {
        $str = 'Hebrew - שלום';
        $expect = $this->expect('Hebrew - \\uc0{\\u1513}\\uc0{\\u1500}\\uc0{\\u1493}\\uc0{\\u1501}');
        $this->assertEquals($expect, $this->escapestring($str));
    }

    /**
     * Test tab
     */
    public function testTab()
    {
        $str = "Here's a tab\tfollowed by more characters.";
        $expect = $this->expect("Here's a tab{\\tab}followed by more characters.");
        $this->assertEquals($expect, $this->escapestring($str));
    }
}
