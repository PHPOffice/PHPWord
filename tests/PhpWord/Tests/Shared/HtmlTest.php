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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Shared;

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Shared\Html;

/**
 * Test class for PhpOffice\PhpWord\Shared\Html
 */
class HtmlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test unit conversion functions with various numbers
     */
    public function testAddHtml()
    {
        $content = '';

        // Default
        $section = new Section(1);
        $this->assertCount(0, $section->getElements());

        // Heading
        $styles = array('strong', 'em', 'sup', 'sub');
        for ($level = 1; $level <= 6; $level++) {
            $content .= "<h{$level}>Heading {$level}</h{$level}>";
        }

        // Styles
        $content .= '<p style="text-decoration: underline; text-decoration: line-through; ' .
            'text-align: center; color: #999; background-color: #000;">';
        foreach ($styles as $style) {
            $content .= "<{$style}>{$style}</{$style}>";
        }
        $content .= '</p>';

        // Add HTML
        Html::addHtml($section, $content);
        $this->assertCount(7, $section->getElements());

        // Other parts
        $section = new Section(1);
        $content = '';
        $content .= '<table><tr><th>Header</th><td>Content</td></tr></table>';
        $content .= '<ul><li>Bullet</li><ul><li>Bullet</li></ul></ul>';
        $content .= '<ol><li>Bullet</li></ol>';
        $content .= "'Single Quoted Text'";
        $content .= '"Double Quoted Text"';
        $content .= '& Ampersand';
        $content .= '&lt;&gt;&ldquo;&lsquo;&rsquo;&laquo;&raquo;&lsaquo;&rsaquo;';
        $content .= '&amp;&bull;&deg;&hellip;&trade;&copy;&reg;&mdash;';
        $content .= '&ndash;&nbsp;&emsp;&ensp;&sup2;&sup3;&frac14;&frac12;&frac34;';
        Html::addHtml($section, $content);
    }
}
