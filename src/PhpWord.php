<?php
/**
 * PhpWord
 *
 * Copyright (c) 2014 PhpWord
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
 * @category   PhpWord
 * @package    PhpWord
 * @copyright  Copyright (c) 2014 PhpWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\DocumentProperties;
use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\Section;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Template;

// @codeCoverageIgnoreStart
if (!defined('PHPWORD_BASE_DIR')) {
    define('PHPWORD_BASE_DIR', \realpath(__DIR__) . \DIRECTORY_SEPARATOR);
    require \PHPWORD_BASE_DIR . 'Autoloader.php';
    PhpOffice\PhpWord\Autoloader::register();
}
// @codeCoverageIgnoreEnd

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
     * @var PhpOffice\PhpWord\DocumentProperties
     */
    private $_documentProperties;

    /**
     * @var string
     */
    private $_defaultFontName;

    /**
     * @var int
     */
    private $_defaultFontSize;

    /**
     * @var PhpOffice\PhpWord\Section[]
     */
    private $_sections = array();

    public function __construct()
    {
        $this->_documentProperties = new DocumentProperties();
        $this->_defaultFontName = self::DEFAULT_FONT_NAME;
        $this->_defaultFontSize = self::DEFAULT_FONT_SIZE;
    }

    /**
     * @return PhpOffice\PhpWord\DocumentProperties
     */
    public function getDocumentProperties()
    {
        return $this->_documentProperties;
    }

    /**
     * @param  PhpOffice\PhpWord\DocumentProperties $documentProperties
     * @return PhpOffice\PhpWord
     */
    public function setDocumentProperties(DocumentProperties $documentProperties)
    {
        $this->_documentProperties = $documentProperties;

        return $this;
    }

    /**
     * @param  PhpOffice\PhpWord\Section\Settings $settings
     * @return PhpOffice\PhpWord\Section
     */
    public function createSection($settings = null)
    {
        $section = new Section(\count($this->_sections) + 1, $settings);
        $this->_sections[] = $section;

        return $section;
    }

    /**
     * @return string
     */
    public function getDefaultFontName()
    {
        return $this->_defaultFontName;
    }

    /**
     * @param string $fontName
     */
    public function setDefaultFontName($fontName)
    {
        $this->_defaultFontName = $fontName;
    }

    /**
     * @return string
     */
    public function getDefaultFontSize()
    {
        return $this->_defaultFontSize;
    }

    /**
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
     * @param $styles array
     */
    public function addFontStyle($styleName, $styleFont, $styleParagraph = null)
    {
        Style::addFontStyle($styleName, $styleFont, $styleParagraph);
    }

    /**
     * Adds a table style definition to styles.xml
     *
     * @param $styleName string
     * @param $styles array
     */
    public function addTableStyle($styleName, $styleTable, $styleFirstRow = null)
    {
        Style::addTableStyle($styleName, $styleTable, $styleFirstRow);
    }

    /**
     * Adds a heading style definition to styles.xml
     *
     * @param $titleCount int
     * @param $styles array
     */
    public function addTitleStyle($titleCount, $styleFont, $styleParagraph = null)
    {
        Style::addTitleStyle($titleCount, $styleFont, $styleParagraph);
    }

    /**
     * Adds a hyperlink style to styles.xml
     *
     * @param $styleName string
     * @param $styles array
     */
    public function addLinkStyle($styleName, $styles)
    {
        Style::addLinkStyle($styleName, $styles);
    }

    /**
     * @return PhpOffice\PhpWord\Section[]
     */
    public function getSections()
    {
        return $this->_sections;
    }

    /**
     * @param  string $filename Fully qualified filename.
     * @return PhpOffice\PhpWord\Template
     * @throws PhpOffice\PhpWord\Exceptions\Exception
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