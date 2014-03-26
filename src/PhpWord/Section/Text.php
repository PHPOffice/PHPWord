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
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.9.0
 */

namespace PhpOffice\PhpWord\Section;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Text element
 */
class Text
{
    /**
     * Text content
     *
     * @var string
     */
    private $text;

    /**
     * Text style
     *
     * @var \PhpOffice\PhpWord\Style\Font
     */
    private $fontStyle;

    /**
     * Paragraph style
     *
     * @var \PhpOffice\PhpWord\Style\Paragraph
     */
    private $paragraphStyle;

    /**
     * Create a new Text Element
     *
     * @param string $text
     * @param null|array|\PhpOffice\PhpWord\Style\Font $fontStyle
     * @param null|array|\PhpOffice\PhpWord\Style\Paragraph $paragraphStyle
     */
    public function __construct($text = null, $fontStyle = null, $paragraphStyle = null)
    {
        $this->setText($text);
        $paragraphStyle = $this->setParagraphStyle($paragraphStyle);
        $this->setFontStyle($fontStyle, $paragraphStyle);
    }

    /**
     * Set Text style
     *
     * @param null|array|\PhpOffice\PhpWord\Style\Font $style
     * @param null|array|\PhpOffice\PhpWord\Style\Paragraph $paragraphStyle
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setFontStyle($style = null, $paragraphStyle = null)
    {
        if ($style instanceof Font) {
            $this->fontStyle = $style;
            $this->setParagraphStyle($paragraphStyle);
        } elseif (is_array($style)) {
            $this->fontStyle = new Font('text', $paragraphStyle);
            $this->fontStyle->setArrayStyle($style);
        } elseif (null === $style) {
            $this->fontStyle = new Font('text', $paragraphStyle);
        } else {
            $this->fontStyle = $style;
            $this->setParagraphStyle($paragraphStyle);
        }
        return $this->fontStyle;
    }

    /**
     * Get Text style
     *
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function getFontStyle()
    {
        return $this->fontStyle;
    }

    /**
     * Set Paragraph style
     *
     * @param null|array|\PhpOffice\PhpWord\Style\Paragraph $style
     * @return null|\PhpOffice\PhpWord\Style\Paragraph
     */
    public function setParagraphStyle($style = null)
    {
        if (is_array($style)) {
            $this->paragraphStyle = new Paragraph;
            $this->paragraphStyle->setArrayStyle($style);
        } elseif ($style instanceof Paragraph) {
            $this->paragraphStyle = $style;
        } elseif (null === $style) {
            $this->paragraphStyle = new Paragraph;
        } else {
            $this->paragraphStyle = $style;
        }
        return $this->paragraphStyle;
    }

    /**
     * Get Paragraph style
     *
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->paragraphStyle;
    }

    /**
     * Set text content
     *
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get Text content
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
