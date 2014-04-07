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
 * @since   0.9.2
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
     * @return \DOMDocument
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
    public function getElements($path, \DOMNode $context = null)
    {
        if ($this->dom === null) {
            return array();
        }
        if ($this->xpath === null) {
            $this->xpath = new \DOMXpath($this->dom);
        }

        return $this->xpath->query($path, $context);
    }

    /**
     * Get elements
     *
     * @param string $path
     * @return \DOMNodeList
     */
    public function getElement($path, \DOMNode $context = null)
    {
        $elements = $this->getElements($path, $context);
        if ($elements->length > 0) {
            return $elements->item(0);
        } else {
            return false;
        }
    }

    /**
     * Get element attribute
     *
     * @param string|\DOMNode $path
     * @param string $attribute
     * @return null|string
     */
    public function getAttribute($path, $attribute, \DOMNode $context = null)
    {
        if ($path instanceof \DOMNode) {
            $return = $path->getAttribute($attribute);
        } else {
            $elements = $this->getElements($path, $context);
            if ($elements->length > 0) {
                $return = $elements->item(0)->getAttribute($attribute);
            } else {
                $return = '';
            }
        }

        return ($return == '') ? null : $return;
    }

    /**
     * Get element value
     *
     * @param string $path
     * @return null|string
     */
    public function getValue($path, \DOMNode $context = null)
    {
        $elements = $this->getElements($path, $context);
        if ($elements->length > 0) {
            $return = $elements->item(0)->nodeValue;
        } else {
            $return = '';
        }

        return ($return == '') ? null : $return;
    }

    /**
     * Count elements
     *
     * @param string $path
     * @return \DOMNodeList
     */
    public function countElements($path, \DOMNode $context = null)
    {
        $elements = $this->getElements($path, $context);
        return $elements->length;
    }

    /**
     * Element exists
     *
     * @param string $path
     * @return \DOMNodeList
     */
    public function elementExists($path, \DOMNode $context = null)
    {
        return $this->getElements($path, $context)->length > 0;
    }
}
