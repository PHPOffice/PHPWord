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

namespace PhpOffice\PhpWord\Writer\WPS\Part;

use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Element\Link;
use PhpOffice\PhpWord\Element\ListItem;
use PhpOffice\PhpWord\Element\PageBreak;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\Title;

/**
 * WPS CONTENTS part writer.
 *
 * The stream is a list of chunks, each of them described by an entry of the
 * index which follows the header of the stream.
 *
 * @since 1.5.0
 */
class Contents extends AbstractPart
{
    /** Magic of the Microsoft Works 7/8 word processor documents. */
    const MAGIC = "CHNKWKS\x00";

    /** Size of the stream header, including the header of the index. */
    const HEADER_SIZE = 0x20;

    /** Size of an entry of the index. */
    const ENTRY_SIZE = 0x18;

    /** End of paragraph. */
    const PARAGRAPH_END = "\x0d";

    /** Page break. */
    const PAGE_BREAK = "\x0c";

    /**
     * Write the CONTENTS stream.
     */
    public function write(): string
    {
        $phpWord = $this->getParentWriter()->getPhpWord();

        $text = '';
        foreach ($phpWord->getSections() as $section) {
            $text .= $this->writeContainer($section);
        }

        return $this->writeChunks([
            'TEXT' => $this->encodeText($text),
            'FONT' => $this->writeFontNames([$phpWord->getDefaultFontName()]),
        ]);
    }

    /**
     * Write the header, the index and the data of the chunks.
     *
     * @param array<string, string> $chunks Chunk name and content pairs
     */
    private function writeChunks(array $chunks): string
    {
        $offset = self::HEADER_SIZE + count($chunks) * self::ENTRY_SIZE;

        $index = '';
        $data = '';
        $id = 0;
        foreach ($chunks as $name => $content) {
            $index .= pack('v', self::ENTRY_SIZE);
            $index .= $name; // name of the chunk
            $index .= pack('vvv', $id++, 0, 0);
            $index .= $name; // type of the chunk
            $index .= pack('VV', $offset + strlen($data), strlen($content));
            $data .= $content;
        }

        $header = self::MAGIC;
        $header .= pack('vvv', 0, 0, count($chunks)); // unknown, unknown, number of chunks
        $header .= str_repeat("\x00", 10); // unknown
        $header .= pack('vv', 0, count($chunks)); // unknown, number of chunks of this index
        $header .= pack('V', 0xFFFFFFFF); // offset of the next index, none

        return $header . $index . $data;
    }

    /**
     * Write the table of the font names.
     *
     * @param string[] $fonts
     */
    private function writeFontNames(array $fonts): string
    {
        $names = '';
        foreach ($fonts as $font) {
            $names .= pack('v', mb_strlen($font));
            $names .= $this->encodeText($font);
            $names .= str_repeat("\x00", 4); // unknown
        }

        $offsets = str_repeat(pack('V', 0), count($fonts));

        return pack('V', strlen($offsets) + strlen($names)) // size of the chunk, without this header
            . pack('V', count($fonts))
            . str_repeat("\x00", 12) // unknown
            . $offsets
            . $names;
    }

    /**
     * Write the paragraphs of a container.
     */
    private function writeContainer(AbstractContainer $container): string
    {
        $text = '';
        foreach ($container->getElements() as $element) {
            if ($element instanceof PageBreak) {
                $text .= self::PAGE_BREAK;
            } elseif ($element instanceof TextBreak) {
                $text .= self::PARAGRAPH_END;
            } elseif ($element instanceof Table) {
                $text .= $this->writeTable($element);
            } elseif ($element instanceof AbstractContainer) {
                $text .= $this->writeParagraph($this->writeInline($element));
            } else {
                $text .= $this->writeParagraph($this->getElementText($element));
            }
        }

        return $text;
    }

    /**
     * Write the paragraphs of the cells of a table.
     */
    private function writeTable(Table $table): string
    {
        $text = '';
        foreach ($table->getRows() as $row) {
            foreach ($row->getCells() as $cell) {
                $text .= $this->writeContainer($cell);
            }
        }

        return $text;
    }

    /**
     * Write a paragraph, ie its text followed by an end of paragraph.
     */
    private function writeParagraph(string $text): string
    {
        if ($text === '') {
            return '';
        }

        return $text . self::PARAGRAPH_END;
    }

    /**
     * Get the text of the elements of a container, without paragraph separator.
     */
    private function writeInline(AbstractContainer $container): string
    {
        $text = '';
        foreach ($container->getElements() as $element) {
            if ($element instanceof AbstractContainer) {
                $text .= $this->writeInline($element);
            } else {
                $text .= $this->getElementText($element);
            }
        }

        return $text;
    }

    /**
     * Get the text of an element, empty for the unsupported ones.
     */
    private function getElementText(AbstractElement $element): string
    {
        if ($element instanceof Text || $element instanceof ListItem) {
            $text = $element->getText();
        } elseif ($element instanceof Link) {
            $text = $element->getText() !== '' ? $element->getText() : $element->getSource();
        } elseif ($element instanceof Title) {
            $title = $element->getText();
            $text = $title instanceof AbstractContainer ? $this->writeInline($title) : $title;
        } else {
            $text = '';
        }

        return $this->sanitizeText((string) $text);
    }

    /**
     * Replace the characters which delimit the paragraphs by spaces.
     */
    private function sanitizeText(string $text): string
    {
        return str_replace(["\r\n", "\r", "\n", self::PAGE_BREAK], ' ', $text);
    }
}
