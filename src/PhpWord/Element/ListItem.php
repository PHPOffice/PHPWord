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
     * @var ListItemStyle
     */
    private $style;

    /**
     * Textrun
     *
     * @var \PhpOffice\PhpWord\Element\Text
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
     * @param mixed $styleFont
     * @param mixed $styleList
     * @param mixed $styleParagraph
     */
    public function __construct($text, $depth = 0, $styleFont = null, $styleList = null, $styleParagraph = null)
    {
        $this->textObject = new Text($text, $styleFont, $styleParagraph);
        $this->depth = $depth;
        $this->style = $this->setStyle(new ListItemStyle(), $styleList, true);
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
