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

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Shared\String;
use PhpOffice\PhpWord\Style\ListItem as ListItemStyle;

/**
 * List item element
 */
class ListItem extends AbstractElement
{
    /**
     * Element style
     *
     * @var \PhpOffice\PhpWord\Style\ListItem
     */
    private $style;

    /**
     * Text object
     *
     * @var \PhpOffice\PhpWord\Element\Text
     */
    private $textObject;

    /**
     * Depth
     *
     * @var int
     */
    private $depth;

    /**
     * Create a new ListItem
     *
     * @param string $text
     * @param int $depth
     * @param mixed $fontStyle
     * @param mixed $listStyle
     * @param mixed $paragraphStyle
     */
    public function __construct($text, $depth = 0, $fontStyle = null, $listStyle = null, $paragraphStyle = null)
    {
        $this->setStyle($listStyle);
        $this->textObject = new Text(String::toUTF8($text), $fontStyle, $paragraphStyle);
        $this->depth = $depth;
    }

    /**
     * Set style
     *
     * @param string|array|\PhpOffice\PhpWord\Style\ListItem $style
     * @return string|\PhpOffice\PhpWord\Style\ListItem
     */
    public function setStyle($style)
    {
        // Version >= 0.10.0 will pass numbering style name. Older version will use old method
        if (!is_null($style) && is_string($style)) {
            $this->style = new ListItemStyle($style);
        } else {
            $this->style = $this->setNewStyle(new ListItemStyle(), $style, true);
        }

        return $this->style;
    }

    /**
     * Get style
     *
     * @return \PhpOffice\PhpWord\Style\ListItem
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set Text style
     *
     * @param string|array|\PhpOffice\PhpWord\Style\Font $style
     * @param string|array|\PhpOffice\PhpWord\Style\Paragraph $paragraphStyle
     * @return string|\PhpOffice\PhpWord\Style\Font
     */
    public function setFontStyle($style = null, $paragraphStyle = null)
    {
        return $this->getTextObject()->setFontStyle($style, $paragraphStyle);
    }

    /**
     * Get Text style
     *
     * @return string|\PhpOffice\PhpWord\Style\Font
     */
    public function getFontStyle()
    {
        return $this->getTextObject()->getFontStyle();
    }

    /**
     * Set Paragraph style
     *
     * @param string|array|\PhpOffice\PhpWord\Style\Paragraph $style
     * @return string|\PhpOffice\PhpWord\Style\Paragraph
     */
    public function setParagraphStyle($style = null)
    {
        $this->getTextObject()->setParagraphStyle($style);
    }

    /**
     * Get Paragraph style
     *
     * @return string|\PhpOffice\PhpWord\Style\Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->getTextObject()->getParagraphStyle();
    }

    /**
     * Get Text object
     *
     * @return \PhpOffice\PhpWord\Element\Text
     */
    public function getTextObject()
    {
        return $this->textObject;
    }

    /**
     * Get depth
     *
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Get text
     *
     * @return string
     * @since 0.11.0
     */
    public function getText()
    {
        return $this->textObject->getText();
    }
}
