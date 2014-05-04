<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Generic element HTML writer
 *
 * Section: Text, TextRun, Link, Title, PreserveText, TextBreak, PageBreak, Table, ListItem, Image, Object, Endnote, Footnote
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
        $html = '';
        $elmName = str_replace('PhpOffice\\PhpWord\\Element\\', '', get_class($this->element));
        $elmWriterClass = 'PhpOffice\\PhpWord\\Writer\\HTML\\Element\\' . $elmName;
        if (class_exists($elmWriterClass) === true) {
            $elmWriter = new $elmWriterClass($this->parentWriter, $this->element, $this->withoutP);
            $html = $elmWriter->write();
        }

        return $html;
    }
}
