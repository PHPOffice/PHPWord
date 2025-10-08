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

namespace PhpOffice\PhpWord\Writer\RTF\Style;

use PhpOffice\PhpWord\SimpleType\Border as BorderType;

/**
 * Border style writer.
 *
 * @since 0.12.0
 */
class Border extends AbstractStyle
{
    /**
     * Type. Can be section, paragraph, font, row, or cell.
     *
     * @var string
     */
    private $type = 'paragraph';

    /**
     * Write style.
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Border) {
            return '';
        }

        $content = '';

        if ($this->type == 'section') {
            // Page border measure
            // 8 = from text, infront off; 32 = from edge, infront on; 40 = from edge, infront off
            $content .= '\pgbrdropt32';
        }

        $sides = ['top', 'left', 'right', 'bottom'];
        $sizeCount = 4;

        if ($this->type == 'font') {
            $sizeCount = 1;
        }

        $sizes = $style->getBorderSize();
        $colors = $style->getBorderColor();
        $styles = $style->getBorderStyle();
        $spaces = $style->getBorderSpace();

        for ($i = 0; $i < $sizeCount; ++$i) {
            $content .= $this->writeSide($sides[$i], $sizes[$i], $colors[$i], $styles[$i], $spaces[$i]);
        }

        return $content;
    }

    /**
     * Write side.
     *
     * @param string $side
     * @param float|int $width
     * @param string $color
     * @param string $style
     * @param float|int $space
     *
     * @return string
     */
    private function writeSide($side, $width, $color, $style, $space)
    {
        $types = [
            'section' => '\pgbrdr',
            'paragraph' => '\brdr',
            'font' => '\chbrdr',
            'row' => '\trbrdr',
            'cell' => '\clbrdr',
        ];

        $styles = [
            BorderType::SINGLE => '\brdrs',
            BorderType::DASH_DOT_STROKED => '\brdrdashdotstr',
            BorderType::DASHED => '\brdrdash',
            BorderType::DASH_SMALL_GAP => '\brdrdashsm',
            BorderType::DOT_DASH => '\brdrdashd',
            BorderType::DOT_DOT_DASH => '\brdrdashdd',
            BorderType::DOTTED => '\brdrdot',
            BorderType::DOUBLE => '\brdrdb',
            BorderType::DOUBLE_WAVE => '\brdrwavydb',
            BorderType::INSET => '\brdrinset',
            BorderType::NIL => '\brdrnil',
            BorderType::NONE => '\brdrnone',
            BorderType::OUTSET => '\brdroutset',
            BorderType::THICK => '\brdrth',
            BorderType::THICK_THIN_LARGE_GAP => '\brdrtnthlg',
            BorderType::THICK_THIN_MEDIUM_GAP => '\brdrtnthmg',
            BorderType::THICK_THIN_SMALL_GAP => '\brdrtnthsg',
            BorderType::THIN_THICK_LARGE_GAP => '\brdrthtnlg',
            BorderType::THIN_THICK_MEDIUM_GAP => '\brdrthtnmg',
            BorderType::THIN_THICK_SMALL_GAP => '\brdrthtnsg',
            BorderType::THIN_THICK_THIN_LARGE_GAP => '\brdrtnthtnlg',
            BorderType::THIN_THICK_THIN_MEDIUM_GAP => '\brdrtnthtnmg',
            BorderType::THIN_THICK_THIN_SMALL_GAP => '\brdrtnthtnsg',
            BorderType::THREE_D_EMBOSS => '\brdremboss',
            BorderType::THREE_D_ENGRAVE => '\brdrengrave',
            BorderType::TRIPLE => '\brdrtriple',
            BorderType::WAVE => '\brdrwavy',
        ];

        /** @var \PhpOffice\PhpWord\Writer\RTF $parentWriter */
        $parentWriter = $this->getParentWriter();
        $colorIndex = 0;
        if ($parentWriter !== null) {
            $index = array_search($color, $parentWriter->getColorTable());
            if ($index !== false) {
                $colorIndex = $index + 1;
            }
        }

        $content = '';
        if (isset($types[$this->type])) {
            if ($this->type == 'font') {
                $content .= $types[$this->type]; // character borders cannot vary by side
            } else {
                $content .= $types[$this->type] . substr($side, 0, 1);
            }
        } else {
            return '';
        }

        if (isset($styles[$style])) {
            $content .= $styles[$style];
        } else {
            $content .= '\brdrs'; // default style
        }
        $content .= $this->getValueIf($width !== null, '\brdrw' . round($width)); // Width
        $content .= '\brdrcf' . $colorIndex; // Color

        // Space
        if ($this->type == 'section') {
            $space = $space !== null ? $space : '480';
        } elseif ($this->type == 'paragraph') {
            if ($side == 'top' || $side == 'bottom') {
                $space = $space !== null ? $space : '20';
            } elseif ($side == 'left' || $side == 'right') {
                $space = $space !== null ? $space : '80';
            }
        }
        if (in_array($this->type, ['section', 'paragraph'])) {
            $content .= $this->getValueIf($space !== null, '\brsp' . round($space ?? 0));
        }

        $content .= ' ';

        return $content;
    }

    /**
     * Set type.
     *
     * @param string $value
     */
    public function setType($value = 'paragraph'): void
    {
        $this->type = $value;
    }
}
