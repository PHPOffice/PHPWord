<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Section;

/**
 * List item element
 */
class ListItem
{
    /**
     * ListItem Style
     *
     * @var \PhpOffice\PhpWord\Style\ListItem
     */
    private $_style;

    /**
     * Textrun
     *
     * @var \PhpOffice\PhpWord\Section\Text
     */
    private $_textObject;

    /**
     * ListItem Depth
     *
     * @var int
     */
    private $_depth;


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
        $this->_style = new \PhpOffice\PhpWord\Style\ListItem();
        $this->_textObject = new Text($text, $styleFont, $styleParagraph);
        $this->_depth = $depth;

        if (!is_null($styleList) && is_array($styleList)) {
            foreach ($styleList as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->_style->setStyleValue($key, $value);
            }
        }
    }

    /**
     * Get ListItem style
     */
    public function getStyle()
    {
        return $this->_style;
    }

    /**
     * Get ListItem TextRun
     */
    public function getTextObject()
    {
        return $this->_textObject;
    }

    /**
     * Get ListItem depth
     */
    public function getDepth()
    {
        return $this->_depth;
    }
}
