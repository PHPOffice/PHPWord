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
    public function __construct(AbstractElement $element, $withoutP = false)
    {
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
            $elmWriter = new $elmWriterClass($this->element, $this->withoutP);
            $elmWriter->setParentWriter($this->parentWriter);
            $html = $elmWriter->write();
        }

        return $html;
    }

    /**
     * Set parent writer
     *
     * @param \PhpOffice\PhpWord\Writer\HTML $pWriter
     */
    public function setParentWriter(HTML $writer)
    {
        $this->parentWriter = $writer;
    }

    /**
     * Get parent writer
     *
     * @return \PhpOffice\PhpWord\Writer\HTML
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function getParentWriter()
    {
        if (!is_null($this->parentWriter)) {
            return $this->parentWriter;
        } else {
            throw new Exception("No parent HTML Writer assigned.");
        }
    }
}
