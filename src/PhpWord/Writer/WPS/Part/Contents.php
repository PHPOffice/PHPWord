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
 * Microsoft Works 7/8 CONTENTS stream (CHNKWKS chunks).
 *
 * @see https://github.com/fosnola/libwps (WPS8Parser)
 */
class Contents extends AbstractPart
{
    const MAGIC = "CHNKWKS\x00";

    const HEADER_BYTES = 0x20;

    const INDEX_ENTRY_BYTES = 0x18;

    const PARAGRAPH_MARK = "\x0d";

    const FORM_FEED = "\x0c";

    public function write(): string
    {
        $phpWord = $this->getParentWriter()->getPhpWord();
        $plain = '';
        foreach ($phpWord->getSections() as $section) {
            $plain .= $this->containerText($section);
        }

        return $this->assemble([
            'TEXT' => $this->toUtf16Le($plain),
            'FONT' => $this->fontTable([$phpWord->getDefaultFontName()]),
        ]);
    }

    /**
     * @param array<string, string> $chunks
     */
    private function assemble(array $chunks): string
    {
        $cursor = self::HEADER_BYTES + count($chunks) * self::INDEX_ENTRY_BYTES;
        $index = '';
        $body = '';
        $id = 0;
        foreach ($chunks as $tag => $payload) {
            $index .= pack('v', self::INDEX_ENTRY_BYTES);
            $index .= $tag;
            $index .= pack('vvv', $id++, 0, 0);
            $index .= $tag;
            $index .= pack('VV', $cursor + strlen($body), strlen($payload));
            $body .= $payload;
        }

        $header = self::MAGIC;
        $header .= pack('vvv', 0, 0, count($chunks));
        $header .= str_repeat("\x00", 10);
        $header .= pack('vv', 0, count($chunks));
        $header .= pack('V', 0xFFFFFFFF);

        return $header . $index . $body;
    }

    /**
     * @param string[] $fonts
     */
    private function fontTable(array $fonts): string
    {
        $names = '';
        foreach ($fonts as $font) {
            $names .= pack('v', mb_strlen($font));
            $names .= $this->toUtf16Le($font);
            $names .= str_repeat("\x00", 4);
        }
        $slots = str_repeat(pack('V', 0), count($fonts));

        return pack('V', strlen($slots) + strlen($names))
            . pack('V', count($fonts))
            . str_repeat("\x00", 12)
            . $slots
            . $names;
    }

    private function containerText(AbstractContainer $container): string
    {
        $out = '';
        foreach ($container->getElements() as $element) {
            if ($element instanceof PageBreak) {
                $out .= self::FORM_FEED;
            } elseif ($element instanceof TextBreak) {
                $out .= self::PARAGRAPH_MARK;
            } elseif ($element instanceof Table) {
                $out .= $this->tableText($element);
            } elseif ($element instanceof AbstractContainer) {
                $out .= $this->asParagraph($this->inlineText($element));
            } else {
                $out .= $this->asParagraph($this->elementText($element));
            }
        }

        return $out;
    }

    private function tableText(Table $table): string
    {
        $out = '';
        foreach ($table->getRows() as $row) {
            foreach ($row->getCells() as $cell) {
                $out .= $this->containerText($cell);
            }
        }

        return $out;
    }

    private function asParagraph(string $text): string
    {
        return $text === '' ? '' : $text . self::PARAGRAPH_MARK;
    }

    private function inlineText(AbstractContainer $container): string
    {
        $out = '';
        foreach ($container->getElements() as $element) {
            if ($element instanceof AbstractContainer) {
                $out .= $this->inlineText($element);
            } else {
                $out .= $this->elementText($element);
            }
        }

        return $out;
    }

    private function elementText(AbstractElement $element): string
    {
        if ($element instanceof Text || $element instanceof ListItem) {
            $text = $element->getText();
        } elseif ($element instanceof Link) {
            $text = $element->getText() !== '' ? $element->getText() : $element->getSource();
        } elseif ($element instanceof Title) {
            $title = $element->getText();
            $text = $title instanceof AbstractContainer ? $this->inlineText($title) : $title;
        } else {
            $text = '';
        }

        return $this->flatten((string) $text);
    }

    private function flatten(string $text): string
    {
        return str_replace(["\r\n", "\r", "\n", self::FORM_FEED], ' ', $text);
    }
}
