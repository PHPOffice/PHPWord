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

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Exception\Exception;

/**
 * PHPWord main class
 *
 * @method Collection\Titles getTitles()
 * @method Collection\Footnotes getFootnotes()
 * @method Collection\Endnotes getEndnotes()
 * @method int addTitle(Element\Title $title)
 * @method int addFootnote(Element\Footnote $footnote)
 * @method int addEndnote(Element\Endnote $endnote)
 *
 * @method Style\Paragraph addParagraphStyle(string $styleName, array $styles)
 * @method Style\Font addFontStyle(string $styleName, mixed $fontStyle, mixed $paragraphStyle = null)
 * @method Style\Font addLinkStyle(string $styleName, mixed $styles)
 * @method Style\Font addTitleStyle(int $depth, mixed $fontStyle, mixed $paragraphStyle = null)
 * @method Style\Table addTableStyle(string $styleName, mixed $styleTable, mixed $styleFirstRow = null)
 * @method Style\Numbering addNumberingStyle(string $styleName, mixed $styles)
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
     * Collections
     *
     * @var array
     */
    private $collections = array();

    /**
     * Create new instance
     *
     * Collections are created dynamically
     */
    public function __construct()
    {
        $this->documentProperties = new DocumentProperties();

        $collections = array('Titles', 'Footnotes', 'Endnotes');
        foreach ($collections as $collection) {
            $class = 'PhpOffice\\PhpWord\\Collection\\' . $collection;
            $this->collections[$collection] = new $class();
        }
    }

    /**
     * Dynamic function call to reduce static dependency
     *
     * Usage:
     * - Getting and adding collections (Titles, Footnotes, and Endnotes)
     * - Adding style
     *
     * @param mixed $function
     * @param mixed $args
     * @return mixed
     *
     * @since 0.12.0
     */
    public function __call($function, $args)
    {
        $function = strtolower($function);

        $getCollection = array();
        $addCollection = array();
        $addStyle = array();

        $collections = array('Title', 'Footnote', 'Endnote');
        foreach ($collections as $collection) {
            $getCollection[] = strtolower("get{$collection}s");
            $addCollection[] = strtolower("add{$collection}");
        }

        $styles = array('Paragraph', 'Font', 'Table', 'Numbering', 'Link', 'Title');
        foreach ($styles as $style) {
            $addStyle[] = strtolower("add{$style}style");
        }

        // Run get collection method
        if (in_array($function, $getCollection)) {
            $key = ucfirst(str_replace('get', '', $function));

            return $this->collections[$key];
        }

        // Run add collection item method
        if (in_array($function, $addCollection)) {
            $key = ucfirst(str_replace('add', '', $function) . 's');

            /** @var \PhpOffice\PhpWord\Collection\AbstractCollection $collectionObject */
            $collectionObject = $this->collections[$key];

            return $collectionObject->addItem($args[0]);
        }

        // Run add style method
        if (in_array($function, $addStyle)) {
            return forward_static_call_array(array('PhpOffice\\PhpWord\\Style', $function), $args);
        }

        // All other methods
        return null;
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
     * @param array $style
     * @return \PhpOffice\PhpWord\Element\Section
     */
    public function addSection($style = null)
    {
        $section = new Section(count($this->sections) + 1, $style);
        $section->setPhpWord($this);
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
     * Save to file or download
     *
     * All exceptions should already been handled by the writers
     *
     * @param string $filename
     * @param string $format
     * @param bool $download
     * @return bool
     */
    public function save($filename, $format = 'Word2007', $download = false)
    {
        $mime = array(
            'Word2007'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'ODText'    => 'application/vnd.oasis.opendocument.text',
            'RTF'       => 'application/rtf',
            'HTML'      => 'text/html',
            'PDF'       => 'application/pdf',
        );

        /** @var \PhpOffice\PhpWord\Writer\WriterInterface $writer */
        $writer = IOFactory::createWriter($this, $format);

        if ($download === true) {
            header("Content-Description: File Transfer");
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Type: ' . $mime[$format]);
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');
            $filename = 'php://output'; // Change filename to force download
        }

        $writer->save($filename);

        return true;
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
