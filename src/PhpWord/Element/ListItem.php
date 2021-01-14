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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Shared\Text as SharedText;
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
     * @param array|string|null $listStyle
     * @param mixed $paragraphStyle
     */
    public function __construct($text, $depth = 0, $fontStyle = null, $listStyle = null, $paragraphStyle = null)
    {
        $this->textObject = new Text(SharedText::toUTF8($text), $fontStyle, $paragraphStyle);
        $this->depth = $depth;

        // Version >= 0.10.0 will pass numbering style name. Older version will use old method
        if (!is_null($listStyle) && is_string($listStyle)) {
            $this->style = new ListItemStyle($listStyle); // @codeCoverageIgnore
        } else {
            $this->style = $this->setNewStyle(new ListItemStyle(), $listStyle, true);
        }
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
