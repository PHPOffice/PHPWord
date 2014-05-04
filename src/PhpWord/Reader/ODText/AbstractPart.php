<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Reader\ODText;

use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Abstract part reader
 */
abstract class AbstractPart extends \PhpOffice\PhpWord\Reader\Word2007\AbstractPart
{
    /**
     * Read w:r (override)
     *
     * @param mixed $parent
     * @param string $docPart
     * @param mixed $paragraphStyle
     */
    protected function readRun(XMLReader $xmlReader, \DOMElement $domNode, &$parent, $docPart, $paragraphStyle = null)
    {
    }

    /**
     * Read w:pPr (override)
     */
    protected function readParagraphStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
    }

    /**
     * Read w:rPr (override)
     */
    protected function readFontStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
    }

    /**
     * Read w:tblPr (override)
     */
    protected function readTableStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
    }
}
