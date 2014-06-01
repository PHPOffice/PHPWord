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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Alignment as AlignmentStyle;
use PhpOffice\PhpWord\Style\Paragraph as ParagraphStyle;

/**
 * Paragraph style writer
 *
 * @since 0.10.0
 */
class Paragraph extends AbstractStyle
{
    /**
     * Without w:pPr
     *
     * @var bool
     */
    private $withoutPPR = false;

    /**
     * Is inline in element
     *
     * @var bool
     */
    private $isInline = false;

    /**
     * Write style
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();

        $isStyleName = $this->isInline && !is_null($this->style) && is_string($this->style);
        if ($isStyleName) {
            if (!$this->withoutPPR) {
                $xmlWriter->startElement('w:pPr');
            }
            $xmlWriter->startElement('w:pStyle');
            $xmlWriter->writeAttribute('w:val', $this->style);
            $xmlWriter->endElement();
            if (!$this->withoutPPR) {
                $xmlWriter->endElement();
            }
        } else {
            $this->writeStyle();
        }
    }

    /**
     * Write full style
     */
    private function writeStyle()
    {
        $style = $this->getStyle();
        if (!$style instanceof ParagraphStyle) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();
        $styles = $style->getStyleValues();

        if (!$this->withoutPPR) {
            $xmlWriter->startElement('w:pPr');
        }

        // Style name
        $xmlWriter->writeElementIf($styles['name'] !== null, 'w:pStyle', 'w:val', $styles['name']);

        // Alignment
        $styleWriter = new Alignment($xmlWriter, new AlignmentStyle(array('value' => $styles['alignment'])));
        $styleWriter->write();

        // Pagination
        $xmlWriter->writeElementIf($styles['pagination']['widowControl'] === false, 'w:widowControl', 'w:val', '0');
        $xmlWriter->writeElementIf($styles['pagination']['keepNext'] === true, 'w:keepNext', 'w:val', '1');
        $xmlWriter->writeElementIf($styles['pagination']['keepLines'] === true, 'w:keepLines', 'w:val', '1');
        $xmlWriter->writeElementIf($styles['pagination']['pageBreak'] === true, 'w:pageBreakBefore', 'w:val', '1');

        // Indentation & spacing
        $this->writeChildStyle($xmlWriter, 'Indentation', $styles['indentation']);
        $this->writeChildStyle($xmlWriter, 'Spacing', $styles['spacing']);

        // Tabs
        $this->writeTabs($xmlWriter, $styles['tabs']);

        // Numbering
        $this->writeNumbering($xmlWriter, $styles['numbering']);

        if (!$this->withoutPPR) {
            $xmlWriter->endElement(); // w:pPr
        }
    }

    /**
     * Write child style
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $name
     * @param mixed $value
     */
    private function writeChildStyle(XMLWriter $xmlWriter, $name, $value)
    {
        if ($value !== null) {
            $class = "PhpOffice\\PhpWord\\Writer\\Word2007\\Style\\" . $name;

            /** @var \PhpOffice\PhpWord\Writer\Word2007\Style\AbstractStyle $writer */
            $writer = new $class($xmlWriter, $value);
            $writer->write();
        }
    }

    /**
     * Write tabs
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Tab[] $tabs
     */
    private function writeTabs(XMLWriter $xmlWriter, $tabs)
    {
        if (!empty($tabs)) {
            $xmlWriter->startElement("w:tabs");
            foreach ($tabs as $tab) {
                $styleWriter = new Tab($xmlWriter, $tab);
                $styleWriter->write();
            }
            $xmlWriter->endElement();
        }
    }

    /**
     * Write numbering
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param array $numbering
     */
    private function writeNumbering(XMLWriter $xmlWriter, $numbering)
    {
        $numStyle = $numbering['style'];
        $numLevel = $numbering['level'];

        /** @var \PhpOffice\PhpWord\Style\Numbering $numbering */
        $numbering = Style::getStyle($numStyle);
        if ($numStyle !== null && $numbering !== null) {
            $xmlWriter->startElement('w:numPr');
            $xmlWriter->startElement('w:numId');
            $xmlWriter->writeAttribute('w:val', $numbering->getIndex());
            $xmlWriter->endElement(); // w:numId
            $xmlWriter->startElement('w:ilvl');
            $xmlWriter->writeAttribute('w:val', $numLevel);
            $xmlWriter->endElement(); // w:ilvl
            $xmlWriter->endElement(); // w:numPr

            $xmlWriter->startElement('w:outlineLvl');
            $xmlWriter->writeAttribute('w:val', $numLevel);
            $xmlWriter->endElement(); // w:outlineLvl
        }
    }

    /**
     * Set without w:pPr
     *
     * @param bool $value
     */
    public function setWithoutPPR($value)
    {
        $this->withoutPPR = $value;
    }

    /**
     * Set is inline
     *
     * @param bool $value
     */
    public function setIsInline($value)
    {
        $this->isInline = $value;
    }
}
