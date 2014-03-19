<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

/**
 * Class PHPWord_Section_TextBreak
 */
class PHPWord_Section_TextBreak
{
    /**
     * Paragraph style
     *
     * @var PHPWord_Style_Pagaraph
     */
    private $paragraphStyle = null;

    /**
     * Text style
     *
     * @var PHPWord_Style_Font
     */
    private $fontStyle = null;

    /**
     * Create a new TextBreak Element
     */
    public function __construct($fontStyle = null, $paragraphStyle = null)
    {
        if (!is_null($paragraphStyle)) {
            $paragraphStyle = $this->setParagraphStyle($paragraphStyle);
        }
        if (!is_null($fontStyle)) {
            $this->setFontStyle($fontStyle, $paragraphStyle);
        }
    }

    /**
     * Set Text style
     *
     * @param null|array|\PHPWord_Style_Font $style
     * @param null|array|\PHPWord_Style_Paragraph $paragraphStyle
     * @return PHPWord_Style_Font
     */
    public function setFontStyle($style = null, $paragraphStyle = null)
    {
        if ($style instanceof PHPWord_Style_Font) {
            $this->fontStyle = $style;
            $this->setParagraphStyle($paragraphStyle);
        } elseif (is_array($style)) {
            $this->fontStyle = new PHPWord_Style_Font('text', $paragraphStyle);
            $this->fontStyle->setArrayStyle($style);
        } else {
            $this->fontStyle = $style;
            $this->setParagraphStyle($paragraphStyle);
        }
        return $this->fontStyle;
    }

    /**
     * Get Text style
     *
     * @return PHPWord_Style_Font
     */
    public function getFontStyle()
    {
        return $this->fontStyle;
    }

    /**
     * Set Paragraph style
     *
     * @param   null|array|\PHPWord_Style_Paragraph $style
     * @return  null|\PHPWord_Style_Paragraph
     */
    public function setParagraphStyle($style = null)
    {
        if (is_array($style)) {
            $this->paragraphStyle = new PHPWord_Style_Paragraph;
            $this->paragraphStyle->setArrayStyle($style);
        } elseif ($style instanceof PHPWord_Style_Paragraph) {
            $this->paragraphStyle = $style;
        } else {
            $this->paragraphStyle = $style;
        }
        return $this->paragraphStyle;
    }

    /**
     * Get Paragraph style
     *
     * @return PHPWord_Style_Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->paragraphStyle;
    }
}
