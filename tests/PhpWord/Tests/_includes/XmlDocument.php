<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

/**
 * DOM wrapper class
 */
class XmlDocument
{
    /**
     * Path
     *
     * @var string $path
     */
    private $path;

    /**
     * DOMDocument object
     *
     * @var \DOMDocument
     */
    private $dom;

    /**
     * DOMXpath object
     *
     * @var \DOMXpath
     */
    private $xpath;

    /**
     * File name
     *
     * @var string
     */
    private $file;

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
    public function getFileDom($file = 'word/document.xml')
    {
        if (null !== $this->dom && $file === $this->file) {
            return $this->dom;
        }

        $this->xpath = null;
        $this->file = $file;

        $file = $this->path . '/' . $file;
        $this->dom = new \DOMDocument();
        $this->dom->load($file);
        return $this->dom;
    }

    /**
     * Get node list
     *
     * @param string $path
     * @param string $file
     * @return \DOMNodeList
     */
    public function getNodeList($path, $file = 'word/document.xml')
    {
        if ($this->dom === null || $file !== $this->file) {
            $this->getFileDom($file);
        }

        if (null === $this->xpath) {
            $this->xpath = new \DOMXpath($this->dom);

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
    public function getElement($path, $file = 'word/document.xml')
    {
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
    public function getElementAttribute($path, $attribute, $file = 'word/document.xml')
    {
        return $this->getElement($path, $file)->getAttribute($attribute);
    }

    /**
     * Check if element exists
     *
     * @param   string  $path
     * @param   string  $file
     * @return  string
     */
    public function elementExists($path, $file = 'word/document.xml')
    {
        $nodeList = $this->getNodeList($path, $file);
        return !($nodeList->length == 0);
    }
}
