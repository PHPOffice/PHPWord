<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * @license http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Element\PageBreak;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Writer\WPS\CompoundFile;
use PhpOffice\PhpWord\Writer\WPS\Document;

/** Microsoft Works document writer. */
class WPS extends AbstractWriter implements WriterInterface
{
    /** @var string */
    private $text = '';

    /** @var array<array{start: int, end: int, properties: string}> */
    private $paragraphs = [];

    /** @var array<array{start: int, end: int, properties: string}> */
    private $runs = [];

    /** @var string[] */
    private $fonts = [];

    public function __construct(?PhpWord $phpWord = null)
    {
        $this->setPhpWord($phpWord);
    }

    public function save(string $filename): void
    {
        $document = $this->getPhpWord();
        $this->text = '';
        $this->paragraphs = [];
        $this->runs = [];
        $this->fonts = [];
        $pageProperties = '';
        foreach ($document->getSections() as $index => $section) {
            if ($section->getHeaders() !== [] || $section->getFooters() !== []) {
                throw new Exception('WPS export does not yet support headers or footers.');
            }
            $style = $section->getStyle();
            $properties = pack('v', 0);
            foreach (['PageSizeW', 'PageSizeH', 'MarginTop', 'MarginLeft', 'MarginBottom', 'MarginRight'] as $id => $dimension) {
                // PHPWord uses twips; Works stores 914400 units per inch.
                $properties .= pack('vV', 0x2200 + $id, (int) round($style->{'get' . $dimension}() * 635));
            }
            $properties .= pack('vv', 0x1218, $style->getOrientation() === 'landscape' ? 2 : 1);
            $properties = pack('v', strlen($properties) + 2) . $properties;
            if ($index > 0 && $pageProperties !== $properties) {
                throw new Exception('WPS export does not yet support different page layouts between sections.');
            }
            $pageProperties = $properties;
            if ($index > 0) {
                $this->appendParagraph([['text' => "\f", 'font' => null]], null);
            }
            foreach ($section->getElements() as $element) {
                $this->appendElement($element);
            }
        }
        if ($this->text === '') {
            $this->appendParagraph([], null);
        }
        // Serialize before opening the destination: unsupported content must not truncate it.
        $content = CompoundFile::encode(Document::encode($this->text, $this->paragraphs, $this->runs, $this->fonts, $pageProperties));
        $this->writeFile($this->openFile($filename), $content);
    }

    private function appendElement(AbstractElement $element): void
    {
        if ($element instanceof Text) {
            $this->appendParagraph([['text' => $element->getText() ?? '', 'font' => $element->getFontStyle()]], $element->getParagraphStyle());
        } elseif ($element instanceof TextBreak) {
            $this->appendParagraph([['text' => '', 'font' => $element->getFontStyle()]], $element->getParagraphStyle());
        } elseif ($element instanceof PageBreak) {
            $this->appendParagraph([['text' => "\f", 'font' => null]], null);
        } elseif ($element instanceof TextRun) {
            $parts = [];
            foreach ($element->getElements() as $child) {
                if ($child instanceof Text) {
                    $parts[] = ['text' => $child->getText() ?? '', 'font' => $child->getFontStyle()];
                } elseif ($child instanceof TextBreak) {
                    $parts[] = ['text' => "\n", 'font' => $child->getFontStyle()];
                } else {
                    throw new Exception('WPS export does not yet support ' . get_class($child) . '.');
                }
            }
            $this->appendParagraph($parts, $element->getParagraphStyle());
        } elseif ($element instanceof Title && is_string($element->getText())) {
            $style = Style::getStyle($element->getDepth() === 0 ? 'Title' : 'Heading_' . $element->getDepth());
            $style = $style instanceof Font ? $style : null;
            $this->appendParagraph([['text' => $element->getText(), 'font' => $style]], $style instanceof Font ? $style->getParagraph() : null);
        } else {
            throw new Exception('WPS export does not yet support ' . get_class($element) . '.');
        }
    }

    /**
     * @param array<array{text: string, font: null|Font|string}> $parts
     * @param null|Paragraph|string $style
     */
    private function appendParagraph(array $parts, $style): void
    {
        $start = strlen($this->text);
        foreach ($parts as $part) {
            $text = str_replace(["\r\n", "\r"], "\n", $part['text']);
            $this->appendText($text, $part['font']);
        }
        $this->appendText("\r", $parts === [] ? null : $parts[count($parts) - 1]['font']);
        $paragraph = is_string($style) ? Style::getStyle($style) : $style;
        $alignment = $paragraph instanceof Paragraph ? $paragraph->getAlignment() : 'left';
        $alignment = $alignment ?: 'left';
        $alignments = ['left' => 0, 'start' => 0, 'right' => 1, 'end' => 1, 'center' => 2, 'both' => 3];
        if (!isset($alignments[$alignment])) {
            throw new Exception('Unsupported Works paragraph alignment: ' . $alignment);
        }
        $properties = pack('v3', 0, 0x1204, $alignments[$alignment] ?? 0);
        if ($paragraph instanceof Paragraph) {
            $indents = [($paragraph->getIndentFirstLine() ?? 0) - ($paragraph->getHanging() ?? 0), $paragraph->getIndentLeft() ?? 0, $paragraph->getIndentRight() ?? 0];
            foreach ($indents as $id => $indent) {
                $properties .= pack('vV', 0x220C + $id, (int) round($indent * 635));
            }
        }
        $this->paragraphs[] = ['start' => $start, 'end' => strlen($this->text), 'properties' => pack('v', strlen($properties) + 2) . $properties];
    }

    /** @param null|Font|string $style */
    private function appendText(string $text, $style): void
    {
        if ($text === '') {
            return;
        }
        $font = is_string($style) ? Style::getStyle($style) : $style;
        $font = $font instanceof Font ? $font : new Font();
        $name = $font->getName() ?? $this->getPhpWord()->getDefaultFontName();
        $fontId = array_search($name, $this->fonts, true);
        if ($fontId === false) {
            $fontId = count($this->fonts);
            if ($fontId > 255) {
                throw new Exception('Works supports at most 256 font names in this writer.');
            }
            $this->fonts[] = $name;
        }
        $properties = pack('v3', 0, $font->isBold() ? 0x0A02 : 0x0202, $font->isItalic() ? 0x0A03 : 0x0203);
        $properties .= pack('vV', 0x220C, (int) round(($font->getSize() ?? $this->getPhpWord()->getDefaultFontSize()) * 12700));
        if ($font->isSuperScript() || $font->isSubScript()) {
            $properties .= pack('vv', 0x120F, $font->isSuperScript() ? 1 : 2);
        }
        $properties .= pack('v', $font->isStrikethrough() ? 0x0A10 : 0x0210);
        $underlines = [Font::UNDERLINE_SINGLE => 1, Font::UNDERLINE_DOUBLE => 3];
        if ($font->getUnderline() !== Font::UNDERLINE_NONE) {
            if (!isset($underlines[$font->getUnderline()])) {
                throw new Exception('WPS export supports single and double underline styles.');
            }
            $properties .= pack('vv', 0x121E, $underlines[$font->getUnderline()]);
        }
        $properties .= pack('v5', 0x8A24, 8, 0, 0x1800, $fontId);
        $color = $font->getColor() ?? $this->getPhpWord()->getDefaultFontColor();
        if ($color === 'auto') {
            $color = '000000';
        }
        $rgb = hexdec($color);
        $bgr = (($rgb & 0xFF) << 16) | ($rgb & 0xFF00) | (($rgb >> 16) & 0xFF);
        $properties .= pack('vV', 0x222E, $bgr);
        $start = strlen($this->text);
        $this->text .= Document::encodeText($text);
        $this->runs[] = ['start' => $start, 'end' => strlen($this->text), 'properties' => pack('v', strlen($properties) + 2) . $properties];
    }
}
