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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Writer\HTML;

use PhpOffice\PhpWord\Element\Text as TextElement;
use PhpOffice\PhpWord\Writer\HTML;
use PhpOffice\PhpWord\Writer\HTML\Element\Text;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Element subnamespace
 */
class ElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test unmatched elements
     */
    public function testUnmatchedElements()
    {
        $elements = array('Container', 'Footnote', 'Image', 'Link', 'ListItem', 'Table', 'Title');
        foreach ($elements as $element) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\HTML\\Element\\' . $element;
            $parentWriter = new HTML();
            $newElement = new \PhpOffice\PhpWord\Element\PageBreak();
            $object = new $objectClass($parentWriter, $newElement);

            $this->assertEquals('', $object->write());
        }
    }

    /**
     * Test write element text
     */
    public function testWriteTextElement()
    {
        $object = new Text(new HTML(), new TextElement(htmlspecialchars('A', ENT_COMPAT, 'UTF-8')));
        $object->setOpeningText(htmlspecialchars('-', ENT_COMPAT, 'UTF-8'));
        $object->setClosingText(htmlspecialchars('-', ENT_COMPAT, 'UTF-8'));
        $object->setWithoutP(true);

        $this->assertEquals(htmlspecialchars('-A-', ENT_COMPAT, 'UTF-8'), $object->write());
    }
}
