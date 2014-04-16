<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Settings;

/**
 * XML Reader wrapper
 *
 * @since   0.10.0
 */
class XMLReader
{
    /**
     * DOMDocument object
     *
     * @var \DOMDocument
     */
    private $dom = null;

    /**
     * DOMXpath object
     *
     * @var \DOMXpath
     */
    private $xpath = null;

    /**
     * Get DOMDocument from ZipArchive
     *
     * @param string $zipFile
     * @param string $xmlFile
     * @return \DOMDocument|false
     */
    public function getDomFromZip($zipFile, $xmlFile)
    {
        if (file_exists($zipFile) === false) {
            throw new Exception('Cannot find archive file.');
        }

        $zipClass = Settings::getZipClass();
        $zip = new $zipClass();
        $canOpen = $zip->open($zipFile);
        if ($canOpen === false) {
            throw new Exception('Cannot open archive file.');
        }
        $contents = $zip->getFromName($xmlFile);
        $zip->close();

        if ($contents === false) {
            return false;
        } else {
            $this->dom = new \DOMDocument();
            $this->dom->loadXML($contents);
            return $this->dom;
        }
    }

    /**
     * Get elements
     *
     * @param string $path
     * @return \DOMNodeList
     */
    public function getElements($path, \DOMElement $contextNode = null)
    {
        if ($this->dom === null) {
            return array();
        }
        if ($this->xpath === null) {
            $this->xpath = new \DOMXpath($this->dom);
        }

        return $this->xpath->query($path, $contextNode);
    }

    /**
     * Get element
     *
     * @param string $path
     * @return \DOMElement|null
     */
    public function getElement($path, \DOMElement $contextNode)
    {
        $elements = $this->getElements($path, $contextNode);
        if ($elements->length > 0) {
            return $elements->item(0);
        } else {
            return null;
        }
    }

    /**
     * Get element attribute
     *
     * @param string $attribute
     * @param string $path
     * @return string|null
     */
    public function getAttribute($attribute, \DOMElement $contextNode, $path = null)
    {
        if (is_null($path)) {
            $return = $contextNode->getAttribute($attribute);
        } else {
            $elements = $this->getElements($path, $contextNode);
            if ($elements->length > 0) {
                $return = $elements->item(0)->getAttribute($attribute);
            } else {
                $return = null;
            }
        }

        return ($return == '') ? null : $return;
    }

    /**
     * Get element value
     *
     * @param string $path
     * @return string|null
     */
    public function getValue($path, \DOMElement $contextNode)
    {
        $elements = $this->getElements($path, $contextNode);
        if ($elements->length > 0) {
            return $elements->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * Count elements
     *
     * @param string $path
     * @return integer
     */
    public function countElements($path, \DOMElement $contextNode)
    {
        $elements = $this->getElements($path, $contextNode);

        return $elements->length;
    }

    /**
     * Element exists
     *
     * @param string $path
     * @return boolean
     */
    public function elementExists($path, \DOMElement $contextNode)
    {
        return $this->getElements($path, $contextNode)->length > 0;
    }
}
