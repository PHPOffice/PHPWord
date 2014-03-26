<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.9.0
 */

namespace PhpOffice\PhpWord\Section;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Link element
 */
class Link
{
    /**
     * Link source
     *
     * @var string
     */
    private $_linkSrc;

    /**
     * Link name
     *
     * @var string
     */
    private $_linkName;

    /**
     * Link Relation ID
     *
     * @var string
     */
    private $_rId;

    /**
     * Link style
     *
     * @var \PhpOffice\PhpWord\Style\Font
     */
    private $_styleFont;

    /**
     * Paragraph style
     *
     * @var \PhpOffice\PhpWord\Style\Paragraph
     */
    private $_styleParagraph;


    /**
     * Create a new Link Element
     *
     * @param string $linkSrc
     * @param string $linkName
     * @param mixed $styleFont
     * @param mixed $styleParagraph
     */
    public function __construct($linkSrc, $linkName = null, $styleFont = null, $styleParagraph = null)
    {
        $this->_linkSrc = $linkSrc;
        $this->_linkName = $linkName;

        // Set font style
        if (is_array($styleFont)) {
            $this->_styleFont = new Font('text');

            foreach ($styleFont as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->_styleFont->setStyleValue($key, $value);
            }
        } else {
            $this->_styleFont = $styleFont;
        }

        // Set paragraph style
        if (is_array($styleParagraph)) {
            $this->_styleParagraph = new Paragraph();

            foreach ($styleParagraph as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->_styleParagraph->setStyleValue($key, $value);
            }
        } else {
            $this->_styleParagraph = $styleParagraph;
        }

        return $this;
    }

    /**
     * Get Link Relation ID
     *
     * @return int
     */
    public function getRelationId()
    {
        return $this->_rId;
    }

    /**
     * Set Link Relation ID
     *
     * @param int $rId
     */
    public function setRelationId($rId)
    {
        $this->_rId = $rId;
    }

    /**
     * Get Link source
     *
     * @return string
     */
    public function getLinkSrc()
    {
        return $this->_linkSrc;
    }

    /**
     * Get Link name
     *
     * @return string
     */
    public function getLinkName()
    {
        return $this->_linkName;
    }

    /**
     * Get Text style
     *
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function getFontStyle()
    {
        return $this->_styleFont;
    }

    /**
     * Get Paragraph style
     *
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->_styleParagraph;
    }
}
