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

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Element\AbstractElement as Element;
use PhpOffice\PhpWord\Shared\String;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Abstract element writer
 *
 * @since 0.11.0
 */
abstract class AbstractElement
{
    /**
     * XML writer
     *
     * @var \PhpOffice\PhpWord\Shared\XMLWriter
     */
    private $xmlWriter;

    /**
     * Element
     *
     * @var \PhpOffice\PhpWord\Element\AbstractElement
     */
    private $element;

    /**
     * Without paragraph
     *
     * @var bool
     */
    protected $withoutP = false;

    /**
     * Has page break before
     *
     * @var bool
     */
    private $pageBreakBefore = false;

    /**
     * Write element
     */
    abstract public function write();

    /**
     * Create new instance
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\AbstractElement $element
     * @param bool $withoutP
     */
    public function __construct(XMLWriter $xmlWriter, Element $element, $withoutP = false)
    {
        $this->xmlWriter = $xmlWriter;
        $this->element = $element;
        $this->withoutP = $withoutP;
    }

    /**
     * Get XML Writer
     *
     * @return \PhpOffice\PhpWord\Shared\XMLWriter
     */
    protected function getXmlWriter()
    {
        return $this->xmlWriter;
    }

    /**
     * Get element
     *
     * @return \PhpOffice\PhpWord\Element\AbstractElement
     */
    protected function getElement()
    {
        return $this->element;
    }

    /**
     * Has page break before
     *
     * @return bool
     */
    public function hasPageBreakBefore()
    {
        return $this->pageBreakBefore;
    }

    /**
     * Set page break before
     *
     * @param bool $value
     */
    public function setPageBreakBefore($value = true)
    {
        $this->pageBreakBefore = (bool)$value;
    }

    /**
     * Convert text to valid format
     *
     * @param string $text
     * @return string
     */
    protected function getText($text)
    {
        return String::controlCharacterPHP2OOXML(htmlspecialchars($text));
    }
}
