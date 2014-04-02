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
 * Word2007 document rels part writer
 */
class DocumentRels extends Base
{
    /**
     * Write word/_rels/document.xml.rels
     *
     * @param array $relsCollection
     */
    public function writeDocumentRels($relsCollection)
    {
        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $xmlWriter->startElement('Relationships');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');

        // Write static files
        $staticFiles = array(
            'styles' => 'styles.xml',
            'numbering' => 'numbering.xml',
            'settings' => 'settings.xml',
            'theme' => 'theme/theme1.xml',
            'webSettings' => 'webSettings.xml',
            'fontTable' => 'fontTable.xml',
        );
        $i = 0;
        foreach ($staticFiles as $type => $file) {
            $i++;
            $schema = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/' . $type;
            $this->writeRel($xmlWriter, $i, $schema, $file);
        }

        // Write media relationship (image, oleObject, hyperlink)
        $this->writeMediaRels($xmlWriter, $relsCollection);

        $xmlWriter->endElement(); // Relationships

        return $xmlWriter->getData();
    }

    /**
     * Write header footer rels word/_rels/*.xml.rels
     *
     * @param array $relsCollection
     */
    public function writeHeaderFooterRels($relsCollection)
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
