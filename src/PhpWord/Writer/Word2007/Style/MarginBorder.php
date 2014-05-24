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

/**
 * Margin border style writer
 *
 * @since 0.10.0
 */
class MarginBorder extends AbstractStyle
{
    /**
     * Sizes
     *
     * @var integer[]
     */
    private $sizes = array();

    /**
     * Colors
     *
     * @var string[]
     */
    private $colors = array();

    /**
     * Other attributes
     *
     * @var array
     */
    private $attributes = array();

    /**
     * Write style
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();

        $sides = array('top', 'left', 'right', 'bottom', 'insideH', 'insideV');
        $sizeCount = count($this->sizes) - 1;

        for ($i = 0; $i < $sizeCount; $i++) {
            if ($this->sizes[$i] !== null) {
                $color = null;
                if (isset($this->colors[$i])) {
                    $color = $this->colors[$i];
                }
                $this->writeSide($xmlWriter, $sides[$i], $this->sizes[$i], $color);
            }
        }
    }

    /**
     * Write side
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $side
     * @param int $width
     * @param string $color
     */
    private function writeSide(XMLWriter $xmlWriter, $side, $width, $color = null)
    {
        $xmlWriter->startElement('w:' . $side);
        if (!empty($this->colors)) {
            if ($color === null && !empty($this->attributes)) {
                if (array_key_exists('defaultColor', $this->attributes)) {
                    $color = $this->attributes['defaultColor'];
                }
            }
            $xmlWriter->writeAttribute('w:val', 'single');
            $xmlWriter->writeAttribute('w:sz', $width);
            $xmlWriter->writeAttribute('w:color', $color);
            if (!empty($this->attributes)) {
                if (array_key_exists('space', $this->attributes)) {
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
     * Set sizes
     *
     * @param integer[] $value
     */
    public function setSizes($value)
    {
        $this->sizes = $value;
    }

    /**
     * Set colors
     *
     * @param string[] $value
     */
    public function setColors($value)
    {
        $this->colors = $value;
    }

    /**
     * Set attributes
     *
     * @param array $value
     */
    public function setAttributes($value)
    {
        $this->attributes = $value;
    }
}
