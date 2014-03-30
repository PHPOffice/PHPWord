<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 contenttypes part writer
 */
class ContentTypes extends WriterPart
{
    /**
     * Write [Content_Types].xml
     * @param array $_imageTypes
     * @param array $_objectTypes
     * @param int $_cHdrs
     * @param array $footers
     */
    public function writeContentTypes($_imageTypes, $_objectTypes, $_cHdrs, $footers)
    {
        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Types
        $xmlWriter->startElement('Types');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/content-types');

        // Rels
        $this->_writeDefaultContentType(
            $xmlWriter,
            'rels',
            'application/vnd.openxmlformats-package.relationships+xml'
        );

        // XML
        $this->_writeDefaultContentType(
            $xmlWriter,
            'xml',
            'application/xml'
        );

        // Add media content-types
        foreach ($_imageTypes as $key => $value) {
            $this->_writeDefaultContentType($xmlWriter, $key, $value);
        }

        // Add embedding content-types
        if (count($_objectTypes) > 0) {
            $this->_writeDefaultContentType(
                $xmlWriter,
                'bin',
                'application/vnd.openxmlformats-officedocument.oleObject'
            );
        }

        // DocProps
        $this->_writeOverrideContentType(
            $xmlWriter,
            '/docProps/app.xml',
            'application/vnd.openxmlformats-officedocument.extended-properties+xml'
        );

        $this->_writeOverrideContentType(
            $xmlWriter,
            '/docProps/core.xml',
            'application/vnd.openxmlformats-package.core-properties+xml'
        );

        // Document
        $this->_writeOverrideContentType(
            $xmlWriter,
            '/word/document.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml'
        );

        // Styles
        $this->_writeOverrideContentType(
            $xmlWriter,
            '/word/styles.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml'
        );

        // Numbering
        $this->_writeOverrideContentType(
            $xmlWriter,
            '/word/numbering.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.numbering+xml'
        );

        // Settings
        $this->_writeOverrideContentType(
            $xmlWriter,
            '/word/settings.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.settings+xml'
        );

        // Theme1
        $this->_writeOverrideContentType(
            $xmlWriter,
            '/word/theme/theme1.xml',
            'application/vnd.openxmlformats-officedocument.theme+xml'
        );

        // WebSettings
        $this->_writeOverrideContentType(
            $xmlWriter,
            '/word/webSettings.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.webSettings+xml'
        );

        // Font Table
        $this->_writeOverrideContentType(
            $xmlWriter,
            '/word/fontTable.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.fontTable+xml'
        );

        // Footnotes
        $this->_writeOverrideContentType(
            $xmlWriter,
            '/word/footnotes.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.footnotes+xml'
        );

        for ($i = 1; $i <= $_cHdrs; $i++) {
            $this->_writeOverrideContentType(
                $xmlWriter,
                '/word/header' . $i . '.xml',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.header+xml'
            );
        }

        for ($i = 1; $i <= count($footers); $i++) {
            if (!is_null($footers[$i])) {
                $this->_writeOverrideContentType(
                    $xmlWriter,
                    '/word/footer' . $i . '.xml',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.footer+xml'
                );
            }
        }


        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }

    /**
     * Get image mime type
     *
     * @param  string $pFile Filename
     * @return string Mime Type
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    private function _getImageMimeType($pFile = '')
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
     * @param  \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter XML Writer
     * @param  string $pPartname Part name
     * @param  string $pContentType Content type
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    private function _writeDefaultContentType(XMLWriter $xmlWriter = null, $pPartname = '', $pContentType = '')
    {
        if ($pPartname != '' && $pContentType != '') {
            // Write content type
            $xmlWriter->startElement('Default');
            $xmlWriter->writeAttribute('Extension', $pPartname);
            $xmlWriter->writeAttribute('ContentType', $pContentType);
            $xmlWriter->endElement();
        } else {
            throw new Exception("Invalid parameters passed.");
        }
    }

    /**
     * Write Override XML element
     *
     * @param  \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param  string $pPartname Part name
     * @param  string $pContentType Content type
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    private function _writeOverrideContentType(XMLWriter $xmlWriter = null, $pPartname = '', $pContentType = '')
    {
        if ($pPartname != '' && $pContentType != '') {
            // Write content type
            $xmlWriter->startElement('Override');
            $xmlWriter->writeAttribute('PartName', $pPartname);
            $xmlWriter->writeAttribute('ContentType', $pContentType);
            $xmlWriter->endElement();
        } else {
            throw new Exception("Invalid parameters passed.");
        }
    }
}
