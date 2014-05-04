<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

/**
 * ListItem element HTML writer
 *
 * @since 0.10.0
 */
class ListItem extends Element
{
    /**
     * Write list item
     *
     * @return string
     */
    public function write()
    {
        $text = htmlspecialchars($this->element->getTextObject()->getText());
        $html = '<p>' . $text . '</p>' . PHP_EOL;

        return $html;
    }
}
