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

use PhpOffice\PhpWord\Element\Comment;
use PhpOffice\PhpWord\Writer\Word2007\Element\AbstractElement as Word2007AbstractElement;

/**
 * Abstract element writer.
 *
 * @since 0.11.0
 */
abstract class AbstractElement extends Word2007AbstractElement
{
    protected function writeCommentRangeStart(): void
    {
        if ($this->getElement()->getCommentsRangeStart() === null) {
            return;
        }

        foreach ($this->getElement()->getCommentsRangeStart()->getItems() as $comment) {
            $this->getXmlWriter()->startElement('office:annotation');
            $this->getXmlWriter()->writeAttribute('office:name', $comment->getElementId());
            $this->getXmlWriter()->writeElement('dc:creator', $comment->getAuthor());
            if ($comment->getDate() !== null) {
                $this->getXmlWriter()->writeElement('dc:date', $comment->getDate()->format('Y-m-d\\TH:i:s\\Z'));
            }

            $containerWriter = new Container($this->getXmlWriter(), $comment);
            $containerWriter->write();
            $this->getXmlWriter()->endElement();
        }
    }

    protected function writeCommentRangeEnd(): void
    {
        $element = $this->getElement();
        $comments = $element->getCommentsRangeEnd();
        if ($comments !== null) {
            foreach ($comments->getItems() as $comment) {
                $this->writeCommentRangeEndElement($comment);
            }
        }

        $comments = $element->getCommentsRangeStart();
        if ($comments !== null) {
            foreach ($comments->getItems() as $comment) {
                if ($comment->getEndElement() === null) {
                    $this->writeCommentRangeEndElement($comment);
                }
            }
        }
    }

    private function writeCommentRangeEndElement(Comment $comment): void
    {
        $this->getXmlWriter()->startElement('office:annotation-end');
        $this->getXmlWriter()->writeAttribute('office:name', $comment->getElementId());
        $this->getXmlWriter()->endElement();
    }

    protected function replaceTabs($text, $xmlWriter): void
    {
        if (preg_match('/^ +/', $text, $matches)) {
            $num = strlen($matches[0]);
            $xmlWriter->startElement('text:s');
            $xmlWriter->writeAttributeIf($num > 1, 'text:c', "$num");
            $xmlWriter->endElement();
            $text = preg_replace('/^ +/', '', $text);
        }
        preg_match_all('/([\\s\\S]*?)(\\t|  +| ?$)/', $text, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $this->writeText($match[1]);
            if ($match[2] === '') {
                break;
            } elseif ($match[2] === "\t") {
                $xmlWriter->writeElement('text:tab');
            } elseif ($match[2] === ' ') {
                $xmlWriter->writeElement('text:s');

                break;
            } else {
                $num = strlen($match[2]);
                $xmlWriter->startElement('text:s');
                $xmlWriter->writeAttributeIf($num > 1, 'text:c', "$num");
                $xmlWriter->endElement();
            }
        }
    }
}
