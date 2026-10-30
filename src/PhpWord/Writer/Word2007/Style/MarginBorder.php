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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Margin border style writer.
 *
 * @since 0.10.0
 */
class MarginBorder extends AbstractStyle
{
    /**
     * Sizes.
     *
     * @var int[]
     */
    private $sizes = [];

    /**
     * Colors.
     *
     * @var string[]
     */
    private $colors = [];

    /**
     * Border styles.
     *
     * @var string[]
     */
    private $styles = [];

    /**
     * Other attributes.
     *
     * @var array
     */
    private $attributes = [];

    /**
     * Write style.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();

        // The sides come in the order the schema names them, and `w:bottom` comes
        // before `w:right`. The sizes, the colors and the styles come in the order
        // the style keeps them in, which is top, left, right, bottom, so each side
        // names the place it has in them.
        $sides = [
            'top' => 0,
            'left' => 1,
            'bottom' => 3,
            'right' => 2,
            'insideH' => 4,
            'insideV' => 5,
        ];

        foreach ($sides as $side => $index) {
            $size = $this->sizes[$index] ?? null;
            if ($size !== null) {
                $color = $this->colors[$index] ?? null;
                $style = $this->styles[$index] ?? 'single';
                $this->writeSide($xmlWriter, $side, $size, $color, $style);
            }
        }
    }

    /**
     * Write side.
     *
     * @param string $side
     * @param int $width
     * @param string $color
     * @param string $borderStyle
     */
    private function writeSide(XMLWriter $xmlWriter, $side, $width, $color = null, $borderStyle = 'solid'): void
    {
        $xmlWriter->startElement('w:' . $side);
        if (!empty($this->colors)) {
            if ($color === null && !empty($this->attributes)) {
                if (isset($this->attributes['defaultColor'])) {
                    $color = $this->attributes['defaultColor'];
                }
            }
            $xmlWriter->writeAttribute('w:val', $borderStyle);
            $xmlWriter->writeAttribute('w:sz', $width);
            $xmlWriter->writeAttributeIf($color != null, 'w:color', $color);
            if (!empty($this->attributes)) {
                if (isset($this->attributes['space'])) {
                    $xmlWriter->writeAttribute('w:space', $this->attributes['space']);
                }
            }
        } else {
            $xmlWriter->writeAttribute('w:w', $width);
            $xmlWriter->writeAttribute('w:type', 'dxa');
        }
        $xmlWriter->endElement();
    }

    /**
     * Set sizes.
     *
     * @param int[] $value
     */
    public function setSizes($value): void
    {
        $this->sizes = $value;
    }

    /**
     * Set colors.
     *
     * @param array<null|string> $value
     */
    public function setColors($value): void
    {
        $this->colors = $value;
    }

    /**
     * Set border styles.
     *
     * @param string[] $value
     */
    public function setStyles($value): void
    {
        $this->styles = $value;
    }

    /**
     * Set attributes.
     *
     * @param array $value
     */
    public function setAttributes($value): void
    {
        $this->attributes = $value;
    }
}
