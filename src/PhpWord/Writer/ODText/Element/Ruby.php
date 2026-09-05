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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

use PhpOffice\PhpWord\Element\Ruby as RubyElement;
use PhpOffice\PhpWord\Element\TextRun;

/**
 * Ruby element writer.
 * NOTE: This class will write out a Ruby element in the format {baseText} ({rubyText})
 * just like RTF; however, ODT files natively support Ruby text elements.
 * This implementation should be changed in the future to support ODT's native
 * Ruby elements and usage.
 */
class Ruby extends AbstractElement
{
    /**
     * Write element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        /** @var RubyElement */
        $element = $this->getElement();
        if (!$this->withoutP) {
            $xmlWriter->startElement('text:p'); // text:p
        }
        $xmlWriter->startElement('text:ruby');
        $this->writeRuby($element->getBaseTextRun(), 'text:ruby-base');
        $this->writeRuby($element->getRubyTextRun(), 'text:ruby-text');
        $xmlWriter->endElement(); // text:ruby
        if (!$this->withoutP) {
            $xmlWriter->endElement(); // text:p
        }
    }

    /** @var bool */
    private static $writeAsComment = false;

    /**
     * @param TextRun $textRun
     * @param string $tag
     */
    private function writeRuby($textRun, $tag): void
    {
        $xmlWriter = $this->getXmlWriter();
        /** @var RubyElement */
        $element = $this->getElement();
        $paragraphStyle = $textRun->getParagraphStyle();

        $xmlWriter->startElement($tag); // text:rubyBase or text:rubyText
        if (is_string($paragraphStyle)) {
            $paragraphStyle = trim($paragraphStyle);
            if ($paragraphStyle !== '') {
                $xmlWriter->writeAttribute('text:style-name', $paragraphStyle);
            }
        }

        var_dump($element->getCommentRangeStart());
        if (self::$writeAsComment) {
            // @codeCoverageIgnoreStart
            $this->writeCommentRangeStart();
            $this->replaceTabs(
                $element->getBaseTextRun()->getText(),
                $xmlWriter
            );
            $this->writeText(' (');
            $this->replaceTabs(
                $element->getRubyTextRun()->getText(),
                $xmlWriter
            );
            $this->writeText(')');
            $this->writeCommentRangeEnd();
            // @codeCoverageIgnoreEnd
        } else {
            $this->replaceTabs($textRun->getText(), $xmlWriter);
        }

        $xmlWriter->endElement(); // text:rubyBase or text:rubyText
    }
}
