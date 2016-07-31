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

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\Common\Text as CommonText;
use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Element\AbstractElement as Element;

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
     * @var \PhpOffice\Common\XMLWriter
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
     * Write element
     */
    abstract public function write();

    /**
     * Create new instance
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
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
     * @return \PhpOffice\Common\XMLWriter
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
     * Start w:p DOM element.
     *
     * @uses \PhpOffice\PhpWord\Writer\Word2007\Element\PageBreak::write()
     * @return void
     */
    protected function startElementP()
    {
        if (!$this->withoutP) {
            $this->xmlWriter->startElement('w:p');
            // Paragraph style
            if (method_exists($this->element, 'getParagraphStyle')) {
                $this->writeParagraphStyle();
            }
        }
    }

    /**
     * End w:p DOM element.
     *
     * @return void
     */
    protected function endElementP()
    {
        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }

    /**
     * Write ending.
     *
     * @return void
     */
    protected function writeParagraphStyle()
    {
        $this->writeTextStyle('Paragraph');
    }

    /**
     * Write ending.
     *
     * @return void
     */
    protected function writeFontStyle()
    {
        $this->writeTextStyle('Font');
    }


    /**
     * Write text style.
     *
     * @param string $styleType Font|Paragraph
     * @return void
     */
    private function writeTextStyle($styleType)
    {
        $method = "get{$styleType}Style";
        $class = "PhpOffice\\PhpWord\\Writer\\Word2007\\Style\\{$styleType}";
        $styleObject = $this->element->$method();

        $styleWriter = new $class($this->xmlWriter, $styleObject);
        if (method_exists($styleWriter, 'setIsInline')) {
            $styleWriter->setIsInline(true);
        }

        /** @var \PhpOffice\PhpWord\Writer\Word2007\Style\AbstractStyle $styleWriter */
        $styleWriter->write();
    }

    /**
     * Convert text to valid format
     *
     * @param string $text
     * @return string
     */
    protected function getText($text)
    {
        return CommonText::controlCharacterPHP2OOXML($text);
    }
}
