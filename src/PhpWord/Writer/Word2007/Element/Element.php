<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\Word2007\Part\AbstractPart;

/**
 * Generic element writer
 *
 * @since 0.10.0
 */
class Element
{
    /**
     * XML writer
     *
     * @var \PhpOffice\PhpWord\Shared\XMLWriter
     */
    protected $xmlWriter;

    /**
     * Parent writer
     *
     * @var \PhpOffice\PhpWord\Writer\Word2007\Part\AbstractPart
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
    public function __construct(XMLWriter $xmlWriter, AbstractPart $parentWriter, AbstractElement $element, $withoutP = false)
    {
        $this->xmlWriter = $xmlWriter;
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
        $elmName = str_replace('PhpOffice\\PhpWord\\Element\\', '', get_class($this->element));
        $elmWriterClass = 'PhpOffice\\PhpWord\\Writer\\Word2007\\Element\\' . $elmName;
        if (class_exists($elmWriterClass) === true) {
            $elmWriter = new $elmWriterClass($this->xmlWriter, $this->parentWriter, $this->element, $this->withoutP);
            $elmWriter->write();
        }
    }
}
