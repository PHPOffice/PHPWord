<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord;

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
     * Collection of sections
     *
     * @var \PhpOffice\PhpWord\Element\Section[]
     */
    private $sections = array();

    /**
     * Create new
     */
    public function __construct()
    {
        $this->documentProperties = new DocumentProperties();
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
     * Create new section
     *
     * @param array $settings
     * @return \PhpOffice\PhpWord\Element\Section
     */
    public function addSection($settings = null)
    {
        $section = new Section(count($this->sections) + 1, $settings);
        $this->sections[] = $section;

        return $section;
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
     * @return \PhpOffice\PhpWord\Element\Section[]
     */
    public function getSections()
    {
        return $this->sections;
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
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    public function createSection($settings = null)
    {
        return $this->addSection($settings);
    }
}
