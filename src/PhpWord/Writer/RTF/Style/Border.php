<?php
declare(strict_types=1);
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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\RTF\Style;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Style\BorderSide;

/**
 * Border style writer
 *
 * @since 0.12.0
 */
class Border extends AbstractStyle
{
    /**
     * Sizes
     *
     * @var BorderSide[]
     */
    private $borders = array();

    /**
     * Write style
     *
     * @return string
     */
    public function write()
    {
        $content = '';

        // Page border measure
        // 8 = from text, infront off; 32 = from edge, infront on; 40 = from edge, infront off
        $content .= '\pgbrdropt32';

        foreach ($this->borders as $side => $border) {
            $content .= $this->writeSide($side, $border);
        }

        return $content;
    }

    /**
     * Write side
     */
    private function writeSide(string $side, BorderSide $border): string
    {
        if (!in_array($side, array('top', 'bottom', 'left', 'right'))) {
            throw new Exception(sprintf('Invalid side `%s` provided', $side));
        }

        /** @var \PhpOffice\PhpWord\Writer\RTF $rtfWriter */
        $rtfWriter = $this->getParentWriter();
        $colorIndex = 0;
        if ($rtfWriter !== null) {
            $colorTable = $rtfWriter->getColorTable();
            $index = array_search($border->getColor()->toHexOrName(), $colorTable);
            if ($index !== false && $colorIndex !== null) {
                $colorIndex = $index + 1;
            }
        }

        $content = '';

        $content .= '\pgbrdr' . substr($side, 0, 1);
        $content .= '\brdrs'; // Single-thickness border; @todo Get other type of border
        $content .= '\brdrw' . $border->getSize()->toInt('twip'); // Width
        $content .= '\brdrcf' . $colorIndex; // Color
        $content .= '\brsp480'; // Space in twips between borders and the paragraph (24pt, following OOXML)
        $content .= ' ';

        return $content;
    }

    /**
     * @param BorderSide[] $borders
     */
    public function setBorders(array $borders): self
    {
        foreach ($borders as $side => $border) {
            $this->setBorder($side, $border);
        }

        return $this;
    }

    /**
     * Set sizes.
     */
    public function setBorder(string $side, BorderSide $border): self
    {
        if (!in_array($side, array('top', 'bottom', 'left', 'right'))) {
            throw new Exception(sprintf('Invalid side `%s` provided', $side));
        }

        $this->borders[$side] = $border;

        return $this;
    }
}
