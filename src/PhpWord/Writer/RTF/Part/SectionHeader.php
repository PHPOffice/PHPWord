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
 * @copyright   2019 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\RTF\Part;

use PhpOffice\PhpWord\Element\Header;
use PhpOffice\PhpWord\Writer\RTF\Element\Container;

/**
 * RTF page header writer
 */
class SectionHeader extends AbstractPart
{
    /**
     * Root element name
     *
     * @var string
     */
    protected $rootElement = '\header';

    /**
     * Footer/header element to be written
     *
     * @var \PhpOffice\PhpWord\Element\Header
     */
    protected $element;

    /**
     * Write part
     *
     * @return string
     */
    public function write()
    {
        $content = '{';
        $content .= $this->rootElement;
        $type = $this->element->getType();
        if ($type == Header::FIRST) {
            $content .= 'f';
        } elseif ($type == Header::EVEN) {
            $content .= 'r';
        }

        $containerWriter = new Container($this->getParentWriter(), $this->element);
        $content .= $containerWriter->write();

        $content .= '}' . PHP_EOL;

        return $content;
    }

    /**
     * Set element
     *
     * @param \PhpOffice\PhpWord\Element\Footer|\PhpOffice\PhpWord\Element\Header $element
     * @return self
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }
}
