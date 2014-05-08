<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\Word2007\Element\Element as ElementWriter;
use PhpOffice\PhpWord\Writer\WriterInterface;

/**
 * Word2007 writer part abstract class
 */
abstract class AbstractPart
{
    /**
     * Parent writer
     *
     * @var \PhpOffice\PhpWord\Writer\WriterInterface
     */
    protected $parentWriter;

    /**
     * Set parent writer
     *
     * @param \PhpOffice\PhpWord\Writer\WriterInterface $pWriter
     */
    public function setParentWriter(WriterInterface $pWriter = null)
    {
        $this->parentWriter = $pWriter;
    }

    /**
     * Get parent writer
     *
     * @return \PhpOffice\PhpWord\Writer\WriterInterface
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function getParentWriter()
    {
        if (!is_null($this->parentWriter)) {
            return $this->parentWriter;
        } else {
            throw new Exception("No parent WriterInterface assigned.");
        }
    }

    /**
     * Get XML Writer
     *
     * @return \PhpOffice\PhpWord\Shared\XMLWriter
     */
    protected function getXmlWriter()
    {
        $useDiskCaching = false;
        if (!is_null($this->parentWriter)) {
            if ($this->parentWriter->isUseDiskCaching()) {
                $useDiskCaching = true;
            }
        }
        if ($useDiskCaching) {
            return new XMLWriter(XMLWriter::STORAGE_DISK, $this->parentWriter->getDiskCachingDirectory());
        } else {
            return new XMLWriter(XMLWriter::STORAGE_MEMORY);
        }
    }

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
            'Section'  => array_merge($elmMainCell, array('Table', 'Footnote', 'Title', 'PageBreak', 'TOC', 'TextBox')),
            'Header'   => array_merge($elmMainCell, array('Table', 'PreserveText', 'TextBox')),
            'Footer'   => array_merge($elmMainCell, array('Table', 'PreserveText', 'TextBox')),
            'Cell'     => array_merge($elmMainCell, array('PreserveText', 'Footnote', 'Endnote')),
            'TextBox'  => array_merge($elmMainCell, array('PreserveText', 'Footnote', 'Endnote')),
            'TextRun'  => array_merge($elmCommon, array('Footnote', 'Endnote')),
            'Footnote' => $elmCommon,
            'Endnote'  => $elmCommon,
        );
        $containerName = get_class($container);
        $containerName = substr($containerName, strrpos($containerName, '\\') + 1);
        if (!array_key_exists($containerName, $allowedElements)) {
            throw new Exception('Invalid container.'.$containerName. print_r($allowedElements, true));
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
