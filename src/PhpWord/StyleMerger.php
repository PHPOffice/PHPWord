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

declare(strict_types=1);

namespace PhpOffice\PhpWord;

final class StyleMerger
{

    /**
     * @var \DOMElement $styleElement
     */
    private $styleElement;

    /**
     * @var array<string, \DOMElement>
     */
    private $elements = [];

    public function __construct(string $style)
    {
        $this->styleElement = $this->createStyleElement($style);
        foreach ($this->styleElement->childNodes as $node) {
            if ($node instanceof \DOMElement) {
                $this->elements[$node->tagName] = $node;
            }
        }
    }

    public static function mergeStyles(string $style, string ...$styles): string
    {
        $styleMerger = new self($style);
        foreach ($styles as $styleToMerge) {
            $styleMerger->merge($styleToMerge);
        }

        return $styleMerger->getStyleString();
    }

    public function merge(string $style): self
    {
        $styleElement = $this->createStyleElement($style);
        foreach ($styleElement->childNodes as $node) {
            if ($node instanceof \DOMElement) {
                // @todo Do we need recursive merging for some elements?
                if (!isset($this->elements[$node->tagName])) {
                    $importedNode = $this->styleElement->ownerDocument->importNode($node, TRUE);
                    if (!$importedNode instanceof \DOMElement) {
                        throw new \RuntimeException('Importing node failed');
                    }

                    $this->styleElement->appendChild($importedNode);
                    $this->elements[$node->tagName] = $importedNode;
                }
            }
        }

        return $this;
    }

    private function createStyleElement(string $style): \DOMElement
    {
        if (NULL === $style = preg_replace('/>\s+</', '><', $style)) {
            throw new \RuntimeException('Error processing style');
        }

        $doc = new \DOMDocument();
        $doc->loadXML(
            '<root xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">' . $style . '</root>'
        );

        foreach ($doc->documentElement->childNodes as $node) {
            if ($node instanceof \DOMElement) {
                return $node;
            }
        }

        throw new \RuntimeException('Could not create style element');
    }

    public function getStyleString(): string
    {
        return $this->styleElement->ownerDocument->saveXML($this->styleElement);
    }

}
