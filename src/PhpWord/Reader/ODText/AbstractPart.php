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

namespace PhpOffice\PhpWord\Reader\ODText;

use PhpOffice\PhpWord\Reader\Word2007\AbstractPart as Word2007AbstractPart;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Abstract part reader
 */
abstract class AbstractPart extends Word2007AbstractPart
{
    /**
     * Read w:p (override)
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param mixed $parent
     * @param string $docPart
     *
     * @todo Get font style for preserve text
     */
    protected function readParagraph(XMLReader $xmlReader, \DOMElement $domNode, &$parent, $docPart)
    {
    }

    /**
     * Read w:r (override)
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param mixed $parent
     * @param string $docPart
     * @param mixed $paragraphStyle
     */
    protected function readRun(XMLReader $xmlReader, \DOMElement $domNode, &$parent, $docPart, $paragraphStyle = null)
    {
    }

    /**
     * Read w:tbl (override)
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param mixed $parent
     * @param string $docPart
     */
    protected function readTable(XMLReader $xmlReader, \DOMElement $domNode, &$parent, $docPart)
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
