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

namespace PhpOffice\PhpWord\Writer\ePub3\Part;

/**
 * Class for ePub3 metadata part.
 */
class Meta extends AbstractPart
{
    /**
     * Write part content.
     *
     * @return string
     */
    public function write()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>';
        $content .= '<metadata xmlns="http://www.idpf.org/2007/opf">';
        $content .= '<dc:title>Sample ePub3 Document</dc:title>';
        $content .= '<dc:language>en</dc:language>';
        $content .= '<dc:identifier id="bookid">urn:uuid:12345</dc:identifier>';
        $content .= '<meta property="dcterms:modified">2023-01-01T00:00:00Z</meta>';
        $content .= '</metadata>';

        return $content;
    }
}
