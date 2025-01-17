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

namespace PhpOffice\PhpWord\Writer\EPub3\Part;

/**
 * Class for EPub3 content part.
 */
class Content extends AbstractPart
{
    /**
     * Write part content.
     *
     * @return string
     */
    public function write()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>';
        $content .= '<package xmlns="http://www.idpf.org/2007/opf" version="3.0">';
        $content .= '<metadata xmlns:dc="http://purl.org/dc/elements/1.1/">';
        $content .= '<dc:title>Sample EPub3 Document</dc:title>';
        $content .= '<dc:language>en</dc:language>';
        $content .= '</metadata>';
        $content .= '<manifest>';
        $content .= '<item id="content" href="content.xhtml" media-type="application/xhtml+xml"/>';
        $content .= '</manifest>';
        $content .= '<spine>';
        $content .= '<itemref idref="content"/>';
        $content .= '</spine>';
        $content .= '</package>';

        return $content;
    }
}
