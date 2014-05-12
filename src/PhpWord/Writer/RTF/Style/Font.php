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

namespace PhpOffice\PhpWord\Writer\RTF\Style;

use PhpOffice\PhpWord\PhpWord;

/**
 * RTF font style writer
 *
 * @since 0.11.0
 */
class Font extends AbstractStyle
{
    /**
     * @var int Font name index
     */
    private $nameIndex = 0;

    /**
     * @var int Font color index
     */
    private $colorIndex = 0;

    /**
     * Write style
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Font) {
            return;
        }

        $content = '';
        $content .= '\cf' . $this->colorIndex;
        $content .= '\f' . $this->nameIndex;
        $content .= $this->getValueIf($style->isBold(), '\b');
        $content .= $this->getValueIf($style->isItalic(), '\i');

        $size = $style->getSize();
        $content .= $this->getValueIf(is_numeric($size), '\fs' . ($size * 2));

        return $content;
    }

    /**
     * Write end style
     *
     * @return string
     */
    public function writeEnd()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Font) {
            return;
        }

        $content = '';
        $content .= '\cf0';
        $content .= '\f0';
        $content .= $this->getValueIf($style->isBold(), '\b0');
        $content .= $this->getValueIf($style->isItalic(), '\i0');

        $size = $style->getSize();
        $content .= $this->getValueIf(is_numeric($size), '\fs' . (PhpWord::DEFAULT_FONT_SIZE * 2));

        return $content;
    }

    /**
     * Set font name index
     *
     * @param int $value
     */
    public function setNameIndex($value = 0)
    {
        $this->nameIndex = $value;
    }

    /**
     * Set font color index
     *
     * @param int $value
     */
    public function setColorIndex($value = 0)
    {
        $this->colorIndex = $value;
    }
}
