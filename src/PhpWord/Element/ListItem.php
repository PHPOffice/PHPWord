<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\ListItem as ListItemStyle;

/**
 * List item element
 */
class ListItem extends AbstractElement
{
    /**
     * ListItem Style
     *
     * @var \PhpOffice\PhpWord\Style\ListItem
     */
    private $style;

    /**
     * Textrun
     *
     * @var Text
     */
    private $textObject;

    /**
     * ListItem Depth
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
        $this->textObject = new Text($text, $fontStyle, $paragraphStyle);
        $this->depth = $depth;

        // Version >= 0.10.0 will pass numbering style name. Older version will use old method
        if (!is_null($listStyle) && is_string($listStyle)) {
            $this->style = new ListItemStyle($listStyle);
        } else {
            $this->style = $this->setStyle(new ListItemStyle(), $listStyle, true);
        }
    }

    /**
     * Get ListItem style
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Get ListItem TextRun
     */
    public function getTextObject()
    {
        return $this->textObject;
    }

    /**
     * Get ListItem depth
     */
    public function getDepth()
    {
        return $this->depth;
    }
}
