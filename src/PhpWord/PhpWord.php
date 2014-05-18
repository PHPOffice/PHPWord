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

/**
 * PHPWord main class
 */
class PhpWord
{
    /**
     * Default font settings
     *
     * @const string|int
     * @deprecated 0.11.0 Use Settings constants
     */
    const DEFAULT_FONT_NAME = Settings::DEFAULT_FONT_NAME;
    const DEFAULT_FONT_SIZE = Settings::DEFAULT_FONT_SIZE;
    const DEFAULT_FONT_COLOR = Settings::DEFAULT_FONT_COLOR;
    const DEFAULT_FONT_CONTENT_TYPE = Settings::DEFAULT_FONT_CONTENT_TYPE;

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
     * Create new
     */
    public function __construct()
    {
        $this->documentProperties = new DocumentProperties();
        $this->titles = new Titles();
        $this->footnotes = new Footnotes();
        $this->endnotes = new Endnotes();
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
        return Settings::getDefaultFontName();
    }

    /**
     * Set default font name
     *
     * @param string $fontName
     */
    public function setDefaultFontName($fontName)
    {
        Settings::setDefaultFontName($fontName);
    }

    /**
     * Get default font size
     *
     * @return integer
     */
    public function getDefaultFontSize()
    {
        return Settings::getDefaultFontSize();
    }

    /**
     * Set default font size
     *
     * @param int $fontSize
     */
    public function setDefaultFontSize($fontSize)
    {
        Settings::setDefaultFontSize($fontSize);
    }

    /**
     * Set default paragraph style definition to styles.xml
     *
     * @param array $styles Paragraph style definition
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function setDefaultParagraphStyle($styles)
    {
        return Style::setDefaultParagraphStyle($styles);
    }

    /**
     * Adds a paragraph style definition to styles.xml
     *
     * @param string $styleName
     * @param array $styles
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function addParagraphStyle($styleName, $styles)
    {
        return Style::addParagraphStyle($styleName, $styles);
    }

    /**
     * Adds a font style definition to styles.xml
     *
     * @param string $styleName
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function addFontStyle($styleName, $fontStyle, $paragraphStyle = null)
    {
        return Style::addFontStyle($styleName, $fontStyle, $paragraphStyle);
    }

    /**
     * Adds a table style definition to styles.xml
     *
     * @param string $styleName
     * @param mixed $styleTable
     * @param mixed $styleFirstRow
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function addTableStyle($styleName, $styleTable, $styleFirstRow = null)
    {
        return Style::addTableStyle($styleName, $styleTable, $styleFirstRow);
    }

    /**
     * Adds a numbering style
     *
     * @param string $styleName
     * @param mixed $styles
     * @return \PhpOffice\PhpWord\Style\Numbering
     */
    public function addNumberingStyle($styleName, $styles)
    {
        return Style::addNumberingStyle($styleName, $styles);
    }

    /**
     * Adds a hyperlink style to styles.xml
     *
     * @param string $styleName
     * @param mixed $styles
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function addLinkStyle($styleName, $styles)
    {
        return Style::addLinkStyle($styleName, $styles);
    }

    /**
     * Adds a heading style definition to styles.xml
     *
     * @param int $depth
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function addTitleStyle($depth, $fontStyle, $paragraphStyle = null)
    {
        return Style::addTitleStyle($depth, $fontStyle, $paragraphStyle);
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
