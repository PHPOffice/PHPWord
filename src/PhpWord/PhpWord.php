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

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\DocumentProperties;
use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\Section;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Template;

/**
 * PHPWord main class
 */
class PhpWord
{
    const DEFAULT_FONT_COLOR        = '000000';  // HEX
    const DEFAULT_FONT_CONTENT_TYPE = 'default'; // default|eastAsia|cs
    const DEFAULT_FONT_NAME         = 'Arial';
    /**
     * Default font size, in points.
     *
     * OOXML defined font size values in halfpoints, i.e. twice of what PhpWord
     * use, and the conversion will be conducted during XML writing.
     */
    const DEFAULT_FONT_SIZE = 10;

    /**
     * Document properties object
     *
     * @var \PhpOffice\PhpWord\DocumentProperties
     */
    private $_documentProperties;

    /**
     * Default font name
     *
     * @var string
     */
    private $_defaultFontName;

    /**
     * Default font size
     * @var int
     */
    private $_defaultFontSize;

    /**
     * Collection of sections
     *
     * @var \PhpOffice\PhpWord\Section[]
     */
    private $_sections = array();

    /**
     * Create new
     */
    public function __construct()
    {
        $this->_documentProperties = new DocumentProperties();
        $this->_defaultFontName = self::DEFAULT_FONT_NAME;
        $this->_defaultFontSize = self::DEFAULT_FONT_SIZE;
    }

    /**
     * Get document properties object
     *
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function getDocumentProperties()
    {
        return $this->_documentProperties;
    }

    /**
     * Set document properties object
     *
     * @param  \PhpOffice\PhpWord\DocumentProperties $documentProperties
     * @return \PhpOffice\PhpWord\PhpWord
     */
    public function setDocumentProperties(DocumentProperties $documentProperties)
    {
        $this->_documentProperties = $documentProperties;

        return $this;
    }

    /**
     * Create new section
     *
     * @param  \PhpOffice\PhpWord\Section\Settings $settings
     * @return \PhpOffice\PhpWord\Section
     */
    public function createSection($settings = null)
    {
        $section = new Section(\count($this->_sections) + 1, $settings);
        $this->_sections[] = $section;

        return $section;
    }

    /**
     * Get default font name
     *
     * @return string
     */
    public function getDefaultFontName()
    {
        return $this->_defaultFontName;
    }

    /**
     * Set default font name
     *
     * @param string $fontName
     */
    public function setDefaultFontName($fontName)
    {
        $this->_defaultFontName = $fontName;
    }

    /**
     * Get default font size
     *
     * @return string
     */
    public function getDefaultFontSize()
    {
        return $this->_defaultFontSize;
    }

    /**
     * Set default font size
     *
     * @param int $fontSize
     */
    public function setDefaultFontSize($fontSize)
    {
        $this->_defaultFontSize = $fontSize;
    }

    /**
     * Set default paragraph style definition to styles.xml
     *
     * @param array $styles Paragraph style definition
     */
    public function setDefaultParagraphStyle($styles)
    {
        Style::setDefaultParagraphStyle($styles);
    }

    /**
     * Adds a paragraph style definition to styles.xml
     *
     * @param $styleName string
     * @param $styles array
     */
    public function addParagraphStyle($styleName, $styles)
    {
        Style::addParagraphStyle($styleName, $styles);
    }

    /**
     * Adds a font style definition to styles.xml
     *
     * @param $styleName string
     * @param mixed $styleFont
     * @param mixed $styleParagraph
     */
    public function addFontStyle($styleName, $styleFont, $styleParagraph = null)
    {
        Style::addFontStyle($styleName, $styleFont, $styleParagraph);
    }

    /**
     * Adds a table style definition to styles.xml
     *
     * @param string $styleName
     * @param mixed $styleTable
     * @param mixed $styleFirstRow
     */
    public function addTableStyle($styleName, $styleTable, $styleFirstRow = null)
    {
        Style::addTableStyle($styleName, $styleTable, $styleFirstRow);
    }

    /**
     * Adds a heading style definition to styles.xml
     *
     * @param int $titleCount
     * @param mixed $styleFont
     * @param mixed $styleParagraph
     */
    public function addTitleStyle($titleCount, $styleFont, $styleParagraph = null)
    {
        Style::addTitleStyle($titleCount, $styleFont, $styleParagraph);
    }

    /**
     * Adds a hyperlink style to styles.xml
     *
     * @param string $styleName
     * @param mixed $styles
     */
    public function addLinkStyle($styleName, $styles)
    {
        Style::addLinkStyle($styleName, $styles);
    }

    /**
     * Get all sections
     *
     * @return \PhpOffice\PhpWord\Section[]
     */
    public function getSections()
    {
        return $this->_sections;
    }

    /**
     * Load template by filename
     *
     * @param  string $filename Fully qualified filename.
     * @return \PhpOffice\PhpWord\Template
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function loadTemplate($filename)
    {
        if (\file_exists($filename)) {
            return new Template($filename);
        } else {
            throw new Exception("Template file {$filename} not found.");
        }
    }
}
