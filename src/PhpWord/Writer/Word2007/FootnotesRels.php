<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 footnotes rel part writer
 */
class FootnotesRels extends Base
{
    /**
     * Write word/_rels/footnotes.xml.rels
     *
     * @param mixed $relsCollection
     */
    public function writeFootnotesRels($relsCollection)
    {
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('Relationships');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');
        $this->writeMediaRels($xmlWriter, $relsCollection);
        $xmlWriter->endElement();

        return $xmlWriter->getData();
    }
}
