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
 * @version    0.7.0
 */

/**
 * Class PHPWord_Section_Text
 */
class PHPWord_Section_Text
{

    /**
     * Text content
     *
     * @var string
     */
    private $_text;

    /**
     * Text style
     *
     * @var PHPWord_Style_Font
     */
    private $_styleFont;

    /**
     * Paragraph style
     *
     * @var \PHPWord_Style_Paragraph
     */
    private $_styleParagraph;


    /**
     * Create a new Text Element
     *
     * @var string $text
     * @var mixed $style
     */
    public function __construct($text = null, $styleFont = null, $styleParagraph = null)
    {
        // Set font style
        $this->setFontStyle($styleFont);

        // Set paragraph style
        $this->setParagraphStyle($styleParagraph);

        $this->_text = $text;

        return $this;
    }

    /**
     * Get Text style
     *
     * @return PHPWord_Style_Font
     */
    public function getFontStyle()
    {
        return $this->_styleFont;
    }

    /**
     * Set Text style
     *
     * @return PHPWord_Style_Font
     */
    public function setFontStyle($styleFont)
    {
        if (is_array($styleFont)) {
            $this->_styleFont = new PHPWord_Style_Font('text');

            foreach ($styleFont as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->_styleFont->setStyleValue($key, $value);
            }
        } else {
            $this->_styleFont = $styleFont;
        }
    }

    /**
     * Get Paragraph style
     *
     * @return PHPWord_Style_Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->_styleParagraph;
    }

    /**
     * Set Paragraph style
     *
     * @param array|\PHPWord_Style_Paragraph $styleParagraph
     * @return \PHPWord_Style_Paragraph
     * @throws \Exception
     */
    public function setParagraphStyle($styleParagraph)
    {
        if (is_array($styleParagraph)) {
            $this->_styleParagraph = new PHPWord_Style_Paragraph();

            foreach ($styleParagraph as $key => $value) {
                if ($key === 'line-height') {
                    null;
                } elseif (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->_styleParagraph->setStyleValue($key, $value);
            }
        } elseif ($styleParagraph instanceof PHPWord_Style_Paragraph) {
            $this->_styleParagraph = $styleParagraph;
        } else {
            throw new Exception('Expected array or PHPWord_Style_Paragraph');
        }
        return $this->_styleParagraph;
    }

    /**
     * Get Text content
     *
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }
}