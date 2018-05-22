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

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Settings;

/**
 * TextRun element HTML writer
 *
 * @since 0.10.0
 */
class Title extends AbstractElement
{
    use TrackChangeTrait;

    /**
     * Write heading
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Title) {
            return '';
        }

        $tag = 'h' . $this->element->getDepth();

        $text = $this->element->getText();
        if (is_string($text)) {
            if (Settings::isOutputEscapingEnabled()) {
                $text = $this->escaper->escapeHtml($text);
            }
        } elseif ($text instanceof \PhpOffice\PhpWord\Element\AbstractContainer) {
            $writer = new Container($this->parentWriter, $this->element);
            $text = $writer->write();
        }

        $content = "<{$tag}>";
        $content .= $this->writeTrackChangeOpening();
        $content .= "{$text}";
        $content .= $this->writeTrackChangeClosing();
        $content .= "</{$tag}>" . PHP_EOL;

        return $content;
    }
}
