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

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Collection\Endnotes;
use PhpOffice\PhpWord\Collection\Footnotes;
use PhpOffice\PhpWord\Collection\Titles;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Style;

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
     * @var DocumentProperties
     */
    private $documentProperties;

    /**
     * Collection of sections
     *
     * @var \PhpOffice\PhpWord\Element\Section[]
     */
    private $sections = array();

    /**
     * Collection of titles
     *
     * @var \PhpOffice\PhpWord\Collection\Titles
     */
    private $titles;

    /**
     * Collection of footnotes
     *
     * @var \PhpOffice\PhpWord\Collection\Footnotes
     */
    private $footnotes;

    /**
     * Collection of endnotes
     *
     * @var \PhpOffice\PhpWord\Collection\Endnotes
     */
    private $endnotes;

    /**
     * Default font name
     *
     * @var string
     */
    private $defaultFontName;

    /**
     * Default font size
     * @var int
     */
    private $defaultFontSize;

    /**
     * Create new
     */
    public function __construct()
    {
        $this->documentProperties = new DocumentProperties();
        $this->titles = new Titles();
        $this->footnotes = new Footnotes();
        $this->endnotes = new Endnotes();
        $this->defaultFontName = self::DEFAULT_FONT_NAME;
        $this->defaultFontSize = self::DEFAULT_FONT_SIZE;
    }

    /**
     * Get document properties object
     *
     * @return DocumentProperties
     */
    public function getDocumentProperties()
    {
        return $this->documentProperties;
    }

    /**
     * Set document properties object
     *
     * @param DocumentProperties $documentProperties
     * @return self
     */
    public function setDocumentProperties(DocumentProperties $documentProperties)
    {
        $this->documentProperties = $documentProperties;

        return $this;
    }

    /**
     * Get all sections
     *
     * @return \PhpOffice\PhpWord\Element\Section[]
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Create new section
     *
     * @param array $settings
     * @return \PhpOffice\PhpWord\Element\Section
     */
    public function addSection($settings = null)
    {
        $section = new Section(count($this->sections) + 1, $settings);
        $section->setPhpWord($this);
        $this->sections[] = $section;

        return $section;
    }

    /**
     * Get titles
     *
     * @return \PhpOffice\PhpWord\Collection\Titles
     */
    public function getTitles()
    {
        return $this->titles;
    }

    /**
     * Add new title
     *
     * @param \PhpOffice\PhpWord\Element\Title $title
     * @return int
     */
    public function addTitle($title)
    {
        return $this->titles->addItem($title);
    }

    /**
     * Get footnotes
     *
     * @return \PhpOffice\PhpWord\Collection\Footnotes
     */
    public function getFootnotes()
    {
        return $this->footnotes;
    }

    /**
     * Add new footnote
     *
     * @param \PhpOffice\PhpWord\Element\Footnote $footnote
     * @return int
     */
    public function addFootnote($footnote)
    {
        return $this->footnotes->addItem($footnote);
    }

    /**
     * Get endnotes
     *
     * @return \PhpOffice\PhpWord\Collection\Endnotes
     */
    public function getEndnotes()
    {
        return $this->endnotes;
    }

    /**
     * Add new endnote
     *
     * @param \PhpOffice\PhpWord\Element\Endnote $endnote
     * @return int
     */
    public function addEndnote($endnote)
    {
        return $this->endnotes->addItem($endnote);
    }

    /**
     * Get default font name
     *
     * @return string
     */
    public function getDefaultFontName()
    {
        return $this->defaultFontName;
    }

    /**
     * Set default font name
     *
     * @param string $fontName
     */
    public function setDefaultFontName($fontName)
    {
        $this->defaultFontName = $fontName;
    }

    /**
     * Get default font size
     *
     * @return integer
     */
    public function getDefaultFontSize()
    {
        return $this->defaultFontSize;
    }

    /**
     * Set default font size
     *
     * @param int $fontSize
     */
    public function setDefaultFontSize($fontSize)
    {
        $this->defaultFontSize = $fontSize;
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
     * @param string $styleName
     * @param array $styles
     */
    public function addParagraphStyle($styleName, $styles)
    {
        Style::addParagraphStyle($styleName, $styles);
    }

    /**
     * Adds a font style definition to styles.xml
     *
     * @param string $styleName
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     */
    public function addFontStyle($styleName, $fontStyle, $paragraphStyle = null)
    {
        Style::addFontStyle($styleName, $fontStyle, $paragraphStyle);
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
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     */
    public function addTitleStyle($titleCount, $fontStyle, $paragraphStyle = null)
    {
        Style::addTitleStyle($titleCount, $fontStyle, $paragraphStyle);
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
     * Adds a numbering style
     *
     * @param string $styleName
     * @param mixed $styles
     */
    public function addNumberingStyle($styleName, $styles)
    {
        Style::addNumberingStyle($styleName, $styles);
    }

    /**
     * Load template by filename
     *
     * @param  string $filename Fully qualified filename.
     * @return Template
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function loadTemplate($filename)
    {
        if (file_exists($filename)) {
            return new Template($filename);
        } else {
            throw new Exception("Template file {$filename} not found.");
        }
    }

    /**
     * Create new section
     *
     * @param array $settings
     * @return \PhpOffice\PhpWord\Element\Section
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function createSection($settings = null)
    {
        return $this->addSection($settings);
    }
}
