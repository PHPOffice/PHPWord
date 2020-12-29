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
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

/**
 * DOM wrapper class
 */
class XmlDocument
{
    /**
     * Path
     *
     * @var string
     */
    private $path;

    /**
     * DOMDocument object
     *
     * @var \DOMDocument
     */
    private $dom;

    /**
     * DOMXPath object
     *
     * @var \DOMXPath
     */
    private $xpath;

    /**
     * File name
     *
     * @var string
     */
    private $file;

    /**
     * Default file name
     *
     * @var string
     */
    private $defaultFile = 'word/document.xml';

    /**
     * Get default file
     *
     * @return string
     */
    public function getDefaultFile()
    {
        return $this->defaultFile;
    }

    /**
     * Set default file
     *
     * @param string $file
     * @return string
     */
    public function setDefaultFile($file)
    {
        $temp = $this->defaultFile;
        $this->defaultFile = $file;

        return $temp;
    }

    /**
     * Create new instance
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = realpath($path);
    }

    /**
     * Get DOM from file
     *
     * @param string $file
     * @return \DOMDocument
     */
    public function getFileDom($file = '')
    {
        if (!$file) {
            $file = $this->defaultFile;
        }
        if (null !== $this->dom && $file === $this->file) {
            return $this->dom;
        }

        $this->xpath = null;
        $this->file = $file;

        $file = $this->path . '/' . $file;
        if (\PHP_VERSION_ID < 80000) {
            $orignalLibEntityLoader = libxml_disable_entity_loader(false);
        }
        $this->dom = new \DOMDocument();
        $this->dom->load($file);
        if (\PHP_VERSION_ID < 80000) {
            libxml_disable_entity_loader($orignalLibEntityLoader);
        }

        return $this->dom;
    }

    /**
     * Get node list
     *
     * @param string $path
     * @param string $file
     * @return \DOMNodeList
     */
    public function getNodeList($path, $file = '')
    {
        if (!$file) {
            $file = $this->defaultFile;
        }
        if (null === $this->dom || $file !== $this->file) {
            $this->getFileDom($file);
        }

        if (null === $this->xpath) {
            $this->xpath = new \DOMXPath($this->dom);
            $this->xpath->registerNamespace('w14', 'http://schemas.microsoft.com/office/word/2010/wordml');
        }

        return $this->xpath->query($path);
    }

    /**
     * Get element
     *
     * @param string $path
     * @param string $file
     * @return \DOMElement
     */
    public function getElement($path, $file = '')
    {
        if (!$file) {
            $file = $this->defaultFile;
        }
        $elements = $this->getNodeList($path, $file);

        return $elements->item(0);
    }

    /**
     * Get file name
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get element attribute
     *
     * @param   string  $path
     * @param   string  $attribute
     * @param   string  $file
     * @return  string
     */
    public function getElementAttribute($path, $attribute, $file = '')
    {
        if (!$file) {
            $file = $this->defaultFile;
        }

        return $this->getElement($path, $file)->getAttribute($attribute);
    }

    /**
     * Check if element exists
     *
     * @param   string  $path
     * @param   string  $file
     * @return  string
     */
    public function elementExists($path, $file = '')
    {
        if (!$file) {
            $file = $this->defaultFile;
        }
        $nodeList = $this->getNodeList($path, $file);

        return $nodeList->length != 0;
    }

    /**
     * Returns the xml, or part of it as a formatted string
     *
     * @param string $path
     * @param string $file
     * @return string
     */
    public function printXml($path = '/', $file = '')
    {
        if (!$file) {
            $file = $this->defaultFile;
        }
        $element = $this->getElement($path, $file);
        if ($element instanceof \DOMDocument) {
            $element->formatOutput = true;
            $element->preserveWhiteSpace = false;

            return $element->saveXML();
        }

        $newdoc = new \DOMDocument();
        $newdoc->formatOutput = true;
        $newdoc->preserveWhiteSpace = false;
        $node = $newdoc->importNode($element, true);
        $newdoc->appendChild($node);

        return $newdoc->saveXML($node);
    }
}
