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

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Exception\InvalidObjectException;

/**
 * Alternative format content chunk (w:altChunk): embeds a whole existing
 * DOCX document, which Word imports at this place when the file is opened.
 *
 * This is the standard way (ISO/IEC 29500-1, 17.17.2) to merge documents:
 * the embedded document is not rebuilt through the PhpWord object model, so
 * its direct formatting, numbering, images and tables are kept. A style that
 * also exists in the main document takes the main document's definition,
 * which is Word's default when no w:altChunkPr/w:matchSrc is written.
 *
 * The chunk is a block-level element: it is written in the body, before the
 * final section properties (w:sectPr), where the specification requires it.
 * It is only rendered by the Word2007 writer; other writers skip it.
 */
class AltChunk extends AbstractElement
{
    /**
     * Path of the embedded DOCX file.
     *
     * @var string
     */
    private $source;

    /**
     * The embedded file is a relation of the document part.
     *
     * @var bool
     */
    protected $mediaRelation = true;

    /**
     * @param string $source Path of an existing DOCX file
     */
    public function __construct($source)
    {
        $source = (string) $source;
        if (!is_file($source) || strtolower(pathinfo($source, PATHINFO_EXTENSION)) !== 'docx') {
            throw new InvalidObjectException("AltChunk source must be an existing .docx file: {$source}");
        }
        $this->source = $source;
    }

    /**
     * Get the path of the embedded DOCX file.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
}
