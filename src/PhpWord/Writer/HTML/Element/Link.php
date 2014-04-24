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
        $url = $this->element->getLinkSrc();
        $text = $this->element->getLinkName();
        if ($text == '') {
            $text = $url;
        }
        $html = '';
        if (!$this->withoutP) {
            $html .= "<p>" . PHP_EOL;
        }
        $html .= "<a href=\"{$url}\">{$text}</a>" . PHP_EOL;
        if (!$this->withoutP) {
            $html .= "</p>" . PHP_EOL;
        }

        return $html;
    }
}
