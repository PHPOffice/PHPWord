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

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Generic element HTML writer
 *
 * Section: Text, TextRun, Link, Title, PreserveText, TextBreak, PageBreak, Table, ListItem, Image,
 *          Object, Endnote, Footnote
 * Cell: Text, TextRun, Link, PreserveText, TextBreak, ListItem, Image, Object, Endnote, Footnote
 * TextRun: Text, Link, TextBreak, Image, Endnote, Footnote
 *
 * @since 0.10.0
 */
class Element
{
    /**
     * Parent writer
     *
     * @var \PhpOffice\PhpWord\Writer\HTML
     */
    protected $parentWriter;

    /**
     * Element
     *
     * @var \PhpOffice\PhpWord\Element\AbstractElement
     */
    protected $element;

    /**
     * Without paragraph
     *
     * @var bool
     */
    protected $withoutP = false;

    /**
     * Create new instance
     *
     * @param bool $withoutP
     */
    public function __construct(HTML $parentWriter, AbstractElement $element, $withoutP = false)
    {
        $this->parentWriter = $parentWriter;
        $this->element = $element;
        $this->withoutP = $withoutP;
    }

    /**
     * Write element
     *
     * @return string
     */
    public function write()
    {
        $content = '';
        $writerClass = str_replace('\\Element\\', '\\Writer\\HTML\\Element\\', get_class($this->element));
        if (class_exists($writerClass)) {
            $writer = new $writerClass($this->parentWriter, $this->element, $this->withoutP);
            $content = $writer->write();
        }

        return $content;
    }
}
