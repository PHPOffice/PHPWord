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
 * Link element HTML writer
 *
 * @since 0.10.0
 */
class Link extends Element
{
    /**
     * Write link
     *
     * @return string
     */
    public function write()
    {
        $html = "<a href=\"{$this->element->getTarget()}\">{$this->element->getText()}</a>" . PHP_EOL;
        if (!$this->withoutP) {
            $html = '<p>' . $html . '</p>' . PHP_EOL;
        }

        return $html;
    }
}
