<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\Word2007\Element\Element as ElementWriter;

/**
 * Word2007 base part writer
 *
 * Write common parts of document.xml, headerx.xml, and footerx.xml
 */
class Base extends AbstractWriterPart
{
    /**
     * Write container elements
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\AbstractElement $container
     */
    public function writeContainerElements(XMLWriter $xmlWriter, AbstractElement $container)
    {
        // Check allowed elements
        $elmCommon = array('Text', 'Link', 'TextBreak', 'Image', 'Object');
        $elmMainCell = array_merge($elmCommon, array('TextRun', 'ListItem', 'CheckBox'));
        $allowedElements = array(
            'Section'  => array_merge($elmMainCell, array('Table', 'Footnote', 'Title', 'PageBreak', 'TOC')),
            'Header'   => array_merge($elmMainCell, array('Table', 'PreserveText')),
            'Footer'   => array_merge($elmMainCell, array('Table', 'PreserveText')),
            'Cell'     => array_merge($elmMainCell, array('PreserveText', 'Footnote', 'Endnote')),
            'TextRun'  => array_merge($elmCommon, array('Footnote', 'Endnote')),
            'Footnote' => $elmCommon,
            'Endnote'  => $elmCommon,
        );
        $containerName = get_class($container);
        $containerName = substr($containerName, strrpos($containerName, '\\') + 1);
        if (!array_key_exists($containerName, $allowedElements)) {
            throw new Exception('Invalid container.');
        }

        // Loop through elements
        $elements = $container->getElements();
        $withoutP = in_array($containerName, array('TextRun', 'Footnote', 'Endnote')) ? true : false;
        if (count($elements) > 0) {
            foreach ($elements as $element) {
                if ($element instanceof AbstractElement) {
                    $elementWriter = new ElementWriter($xmlWriter, $this, $element, $withoutP);
                    $elementWriter->write();
                }
            }
        } else {
            if ($containerName == 'Cell') {
                $elementWriter = new ElementWriter($xmlWriter, $this, new TextBreak(), $withoutP);
                $elementWriter->write();
            }
        }
    }
}
