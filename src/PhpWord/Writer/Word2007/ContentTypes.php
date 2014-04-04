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
 * Word2007 contenttypes part writer
 */
class ContentTypes extends WriterPart
{
    /**
     * Write [Content_Types].xml
     * @param array $imageTypes
     * @param array $objectTypes
     * @param int $cHdrs
     * @param array $footers
     */
    public function writeContentTypes($imageTypes, $objectTypes, $cHdrs, $footers)
    {

        $OpenXMLPrefix = 'application/vnd.openxmlformats-';
        $WordMLPrefix = $OpenXMLPrefix . 'officedocument.wordprocessingml.';

        $defaults = array(
            'rels' => $OpenXMLPrefix . 'package.relationships+xml',
            'xml'  => 'application/xml',

        );
        if (is_array($imageTypes)) {
            $defaults = array_merge($defaults, $imageTypes);
        }
        if (count($objectTypes) > 0) {
            $defaults['bin'] = $OpenXMLPrefix . 'officedocument.oleObject';
        }
        $overrides = array(
            '/docProps/core.xml'     => $OpenXMLPrefix . 'package.core-properties+xml',
            '/docProps/app.xml'      => $OpenXMLPrefix . 'officedocument.extended-properties+xml',
            '/word/document.xml'     => $WordMLPrefix  . 'document.main+xml',
            '/word/styles.xml'       => $WordMLPrefix  . 'styles+xml',
            '/word/numbering.xml'    => $WordMLPrefix  . 'numbering+xml',
            '/word/settings.xml'     => $WordMLPrefix  . 'settings+xml',
            '/word/theme/theme1.xml' => $OpenXMLPrefix . 'officedocument.theme+xml',
            '/word/webSettings.xml'  => $WordMLPrefix  . 'webSettings+xml',
            '/word/fontTable.xml'    => $WordMLPrefix  . 'fontTable+xml',
            '/word/footnotes.xml'    => $WordMLPrefix  . 'footnotes+xml',
        );
        for ($i = 1; $i <= $cHdrs; $i++) {
            $overrides["/word/header{$i}.xml"] = $WordMLPrefix  . 'header+xml';
        }
        for ($i = 1; $i <= count($footers); $i++) {
            if (!is_null($footers[$i])) {
                $overrides["/word/footer{$i}.xml"] = $WordMLPrefix  . 'footer+xml';
            }
        }

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('Types');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/content-types');
        foreach ($defaults as $key => $value) {
            $this->writeContentType($xmlWriter, true, $key, $value);
        }
        foreach ($overrides as $key => $value) {
            $this->writeContentType($xmlWriter, false, $key, $value);
        }
        $xmlWriter->endElement();

        return $xmlWriter->getData();
    }

    /**
     * Write content types element
     *
     * @param XMLWriter $xmlWriter XML Writer
     * @param boolean $isDefault
     * @param string $partName Part name
     * @param string $contentType Content type
     * @throws Exception
     */
    private function writeContentType(XMLWriter $xmlWriter, $isDefault, $partName = '', $contentType = '')
    {
        if ($partName != '' && $contentType != '') {
            $element = $isDefault ? 'Default' : 'Override';
            $partAttribute = $isDefault ? 'Extension' : 'PartName';
            $xmlWriter->startElement($element);
            $xmlWriter->writeAttribute($partAttribute, $partName);
            $xmlWriter->writeAttribute('ContentType', $contentType);
            $xmlWriter->endElement();
        } else {
            throw new Exception("Invalid parameters passed.");
        }
    }

    /**
     * Get image mime type
     *
     * @param  string $pFile Filename
     * @return string Mime Type
     * @throws Exception
     */
    private function getImageMimeType($pFile = '')
    {
        if (file_exists($pFile)) {
            $image = getimagesize($pFile);
            return image_type_to_mime_type($image[2]);
        } else {
            throw new Exception("File $pFile does not exist");
        }
    }

    /**
     * Write Default XML element
     *
     * @param  XMLWriter $xmlWriter
     * @param  string $partName Part name
     * @param  string $contentType Content type
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    private function writeDefaultContentType(XMLWriter $xmlWriter, $partName = '', $contentType = '')
    {
        $this->writeContentType($xmlWriter, true, $partName, $contentType);
    }

    /**
     * Write Override XML element
     *
     * @param  XMLWriter $xmlWriter
     * @param  string $partName Part name
     * @param  string $contentType Content type
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    private function writeOverrideContentType(XMLWriter $xmlWriter, $partName = '', $contentType = '')
    {
        $this->writeContentType($xmlWriter, false, $partName, $contentType);
    }
}
