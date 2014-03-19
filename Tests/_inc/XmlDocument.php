<?php
namespace PHPWord\Tests;

use DOMDocument;

class XmlDocument
{
    /** @var string $path */
    private $path;

    /** @var \DOMDocument $dom */
    private $dom;

    /** @var \DOMXpath $xpath */
    private $xpath;

    /** @var string $file */
    private $file;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = realpath($path);
    }

    /**
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
        $this->dom = new DOMDocument();
        $this->dom->load($file);
        return $this->dom;
    }

    /**
     * @param   string  $path
     * @param   string  $file
     * @return  \DOMNodeList
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
     * @param   string $path
     * @param   string $file
     * @return  \DOMElement
     */
    public function getElement($path, $file = 'word/document.xml')
    {
        $elements = $this->getNodeList($path, $file);

        return $elements->item(0);
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
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
