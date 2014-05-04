<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

/**
 * TextRun element HTML writer
 *
 * @since 0.10.0
 */
class Title extends Element
{
    /**
     * Write heading
     *
     * @return string
     */
    public function write()
    {
        $tag = 'h' . $this->element->getDepth();
        $text = htmlspecialchars($this->element->getText());
        $html = "<{$tag}>{$text}</{$tag}>" . PHP_EOL;

        return $html;
    }
}
