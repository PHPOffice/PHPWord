<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\PhpWord\PhpWord;

/**
 * Font style writer
 *
 * @since 0.10.0
 */
class Font extends AbstractStyle
{
    /**
     * Is automatic style
     */
    private $isAuto = false;

    /**
     * Write style
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Font)) {
            return;
        }

        $this->xmlWriter->startElement('style:style');
        $this->xmlWriter->writeAttribute('style:name', $this->style->getStyleName());
        $this->xmlWriter->writeAttribute('style:family', 'text');
        $this->xmlWriter->startElement('style:text-properties');
        if ($this->style->getName()) {
            $this->xmlWriter->writeAttribute('style:font-name', $this->style->getName());
            $this->xmlWriter->writeAttribute('style:font-name-complex', $this->style->getName());
        }
        if ($this->style->getSize()) {
            $this->xmlWriter->writeAttribute('fo:font-size', ($this->style->getSize()) . 'pt');
            $this->xmlWriter->writeAttribute('style:font-size-asian', ($this->style->getSize()) . 'pt');
            $this->xmlWriter->writeAttribute('style:font-size-complex', ($this->style->getSize()) . 'pt');
        }
        if ($this->style->getColor()) {
            $this->xmlWriter->writeAttribute('fo:color', '#' . $this->style->getColor());
        }
        if ($this->style->getItalic()) {
            $this->xmlWriter->writeAttribute('fo:font-style', 'italic');
            $this->xmlWriter->writeAttribute('style:font-style-asian', 'italic');
            $this->xmlWriter->writeAttribute('style:font-style-complex', 'italic');
        }
        if ($this->style->getBold()) {
            $this->xmlWriter->writeAttribute('fo:font-weight', 'bold');
            $this->xmlWriter->writeAttribute('style:font-weight-asian', 'bold');
        }
        $this->xmlWriter->endElement(); // style:text-properties
        $this->xmlWriter->endElement(); // style:style
    }

    /**
     * Set is automatic style
     *
     * @param bool $value
     */
    public function setIsAuto($value)
    {
        $this->isAuto = $value;
    }
}
