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
class Paragraph extends AbstractStyle
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
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Paragraph)) {
            return;
        }

        $marginTop = is_null($this->style->getSpaceBefore()) ? '0' : round(17.6 / $this->style->getSpaceBefore(), 2);
        $marginBottom = is_null($this->style->getSpaceAfter()) ? '0' : round(17.6 / $this->style->getSpaceAfter(), 2);

        $this->xmlWriter->startElement('style:style');
        $this->xmlWriter->writeAttribute('style:name', $this->style->getStyleName());
        $this->xmlWriter->writeAttribute('style:family', 'paragraph');
        if ($this->isAuto) {
            $this->xmlWriter->writeAttribute('style:parent-style-name', 'Standard');
            $this->xmlWriter->writeAttribute('style:master-page-name', 'Standard');
        }

        $this->xmlWriter->startElement('style:paragraph-properties');
        if ($this->isAuto) {
            $this->xmlWriter->writeAttribute('style:page-number', 'auto');
        } else {
            $this->xmlWriter->writeAttribute('fo:margin-top', $marginTop . 'cm');
            $this->xmlWriter->writeAttribute('fo:margin-bottom', $marginBottom . 'cm');
            $this->xmlWriter->writeAttribute('fo:text-align', $this->style->getAlign());
        }
        $this->xmlWriter->endElement(); //style:paragraph-properties

        $this->xmlWriter->endElement(); //style:style
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
