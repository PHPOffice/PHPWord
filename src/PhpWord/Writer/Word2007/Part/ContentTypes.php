<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 contenttypes part writer
 */
class ContentTypes extends AbstractPart
{
    /**
     * Write [Content_Types].xml
     *
     * @param array $contentTypes
     */
    public function writeContentTypes($contentTypes)
    {
        $openXMLPrefix = 'application/vnd.openxmlformats-';
        $wordMLPrefix  = $openXMLPrefix . 'officedocument.wordprocessingml.';
        $overrides = array(
            '/docProps/core.xml'     => $openXMLPrefix . 'package.core-properties+xml',
            '/docProps/app.xml'      => $openXMLPrefix . 'officedocument.extended-properties+xml',
            '/word/document.xml'     => $wordMLPrefix  . 'document.main+xml',
            '/word/styles.xml'       => $wordMLPrefix  . 'styles+xml',
            '/word/numbering.xml'    => $wordMLPrefix  . 'numbering+xml',
            '/word/settings.xml'     => $wordMLPrefix  . 'settings+xml',
            '/word/theme/theme1.xml' => $openXMLPrefix . 'officedocument.theme+xml',
            '/word/webSettings.xml'  => $wordMLPrefix  . 'webSettings+xml',
            '/word/fontTable.xml'    => $wordMLPrefix  . 'fontTable+xml',
        );

        $defaults = $contentTypes['default'];
        if (!empty($contentTypes['override'])) {
            foreach ($contentTypes['override'] as $key => $val) {
                $overrides[$key] = $wordMLPrefix . $val . '+xml';
            }
        }

        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('Types');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/content-types');

        $this->writeContentType($xmlWriter, $defaults, true);
        $this->writeContentType($xmlWriter, $overrides, false);

        $xmlWriter->endElement(); // Types

        return $xmlWriter->getData();
    }

    /**
     * Write content types element
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter XML Writer
     * @param array $parts
     * @param boolean $isDefault
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    private function writeContentType(XMLWriter $xmlWriter, $parts, $isDefault)
    {
        foreach ($parts as $partName => $contentType) {
            if ($partName != '' && $contentType != '') {
                $partType = $isDefault ? 'Default' : 'Override';
                $partAttribute = $isDefault ? 'Extension' : 'PartName';
                $xmlWriter->startElement($partType);
                $xmlWriter->writeAttribute($partAttribute, $partName);
                $xmlWriter->writeAttribute('ContentType', $contentType);
                $xmlWriter->endElement();
            } else {
                throw new Exception("Invalid parameters passed.");
            }
        }
    }
}
