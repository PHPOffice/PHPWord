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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;

/**
 * DOM wrapper class.
 */
class XmlDocument
{
    /**
     * Path.
     *
     * @var string
     */
    private $path;

    /**
     * DOMDocument object.
     *
     * @var DOMDocument
     */
    private $dom;

    /**
     * DOMXPath object.
     *
     * @var DOMXPath
     */
    private $xpath;

    /**
     * File name.
     *
     * @var string
     */
    private $file;

    /**
     * Default file name.
     *
     * @var string
     */
    private $defaultFile = 'word/document.xml';

    /**
     * Create new instance.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = realpath($path);
    }

    /**
     * Get default file.
     */
    public function getDefaultFile(): string
    {
        return $this->defaultFile;
    }

    /**
     * Set default file.
     */
    public function setDefaultFile(string $file): string
    {
        $temp = $this->defaultFile;

        $this->defaultFile = $file;

        return $temp;
    }

    /**
     * Get file name.
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Get path.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get DOM from file.
     */
    public function getFileDom(string $file = ''): DOMDocument
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
        $this->dom = new DOMDocument();
        $this->dom->load($file);
        if (\PHP_VERSION_ID < 80000) {
            libxml_disable_entity_loader($orignalLibEntityLoader);
        }

        return $this->dom;
    }

    /**
     * Get node list.
     *
     * @return DOMNodeList<DOMNode>
     */
    public function getNodeList(string $path, string $file = ''): DOMNodeList
    {
        if (!$file) {
            $file = $this->defaultFile;
        }
        if (null === $this->dom || $file !== $this->file) {
            $this->getFileDom($file);
        }

        if (null === $this->xpath) {
            $this->xpath = new DOMXPath($this->dom);
            $this->xpath->registerNamespace('w14', 'http://schemas.microsoft.com/office/word/2010/wordml');
        }

        return $this->xpath->query($path);
    }

    /**
     * Get element.
     */
    public function getElement(string $path, string $file = ''): ?DOMElement
    {
        return $this->getNodeList($path, $file)->item(0);
    }

    /**
     * Get element attribute.
     */
    public function getElementAttribute(string $path, string $attribute, string $file = ''): string
    {
        return $this->getElement($path, $file)->getAttribute($attribute);
    }

    /**
     * Check if element exists.
     */
    public function elementExists(string $path, string $file = ''): bool
    {
        $nodeList = $this->getNodeList($path, $file);

        return $nodeList->length != 0;
    }

    /**
     * Returns the xml, or part of it as a formatted string.
     *
     * @return false|string
     */
    public function printXml(string $path = '/', string $file = '')
    {
        $element = $this->getElement($path, $file);

        $newdoc = new DOMDocument();
        $newdoc->formatOutput = true;
        $newdoc->preserveWhiteSpace = false;
        $node = $newdoc->importNode($element, true);
        $newdoc->appendChild($node);

        return $newdoc->saveXML($node);
    }
}
