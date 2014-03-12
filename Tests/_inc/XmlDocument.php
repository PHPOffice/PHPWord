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
     * @param string $path
     * @param string $file
     * @return \DOMElement
     */
    public function getElement($path, $file = 'word/document.xml')
    {
        if ($this->dom === null || $file !== $this->file) {
            $this->getFileDom($file);
        }

        if (null === $this->xpath) {
            $this->xpath = new \DOMXpath($this->dom);

        }

        $elements = $this->xpath->query($path);
        return $elements->item(0);
    }
}
