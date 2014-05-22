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

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Drawing;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Writer\RTF\Element\Container;

/**
 * RTF writer
 *
 * @since 0.7.0
 */
class RTF extends AbstractWriter implements WriterInterface
{
    /**
     * Font table
     *
     * @var array
     */
    private $fontTable = array();

    /**
     * Color table
     *
     * @var array
     */
    private $colorTable = array();

    /**
     * Last paragraph style
     *
     * @var mixed
     */
    private $lastParagraphStyle;

    /**
     * Create new RTF writer
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function __construct(PhpWord $phpWord = null)
    {
        $this->setPhpWord($phpWord);
    }

    /**
     * Save PhpWord to file
     *
     * @param string $filename
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function save($filename = null)
    {
        $filename = $this->getTempFile($filename);
        $hFile = fopen($filename, 'w');
        if ($hFile !== false) {
            fwrite($hFile, $this->writeDocument());
            fclose($hFile);
        } else {
            throw new Exception("Can't open file");
        }
        $this->cleanupTempFile();
    }

    /**
     * Get font table
     */
    public function getFontTable()
    {
        return $this->fontTable;
    }

    /**
     * Get color table
     */
    public function getColorTable()
    {
        return $this->colorTable;
    }

    /**
     * Get last paragraph style
     */
    public function getLastParagraphStyle()
    {
        return $this->lastParagraphStyle;
    }

    /**
     * Set last paragraph style
     *
     * @param mixed $value
     */
    public function setLastParagraphStyle($value = '')
    {
        $this->lastParagraphStyle = $value;
    }

    /**
     * Get all data
     *
     * @return string
     */
    private function writeDocument()
    {
        $this->populateTableGroups();

        // Set the default character set
        $content = '{\rtf1';
        $content .= '\ansi\ansicpg1252'; // Set the default font (the first one)
        $content .= '\deff0'; // Set the default tab size (720 twips)
        $content .= '\deftab720';
        $content .= PHP_EOL;

        // Set the font tbl group
        $content .= '{\fonttbl';
        foreach ($this->fontTable as $idx => $font) {
            $content .= '{\f' . $idx . '\fnil\fcharset0 ' . $font . ';}';
        }
        $content .= '}' . PHP_EOL;

        // Set the color tbl group
        $content .= '{\colortbl ';
        foreach ($this->colorTable as $color) {
            $arrColor = Drawing::htmlToRGB($color);
            $content .= ';\red' . $arrColor[0] . '\green' . $arrColor[1] . '\blue' . $arrColor[2] . '';
        }
        $content .= ';}' . PHP_EOL;

        $content .= '{\*\generator PhpWord;}' . PHP_EOL; // Set the generator

        // Document formatting
        $content .= '\viewkind4'; // Set the view mode of the document
        $content .= '\uc1'; // Set the numberof bytes that follows a unicode character
        $content .= '\pard'; // Resets to default paragraph properties.
        $content .= '\nowidctlpar'; // No widow/orphan control
        $content .= '\lang1036'; // Applies a language to a text run (1036 : French (France))
        $content .= '\kerning1'; // Point size (in half-points) above which to kern character pairs
        $content .= '\fs' . (Settings::getDefaultFontSize() * 2); // Set the font size in half-points
        $content .= PHP_EOL . PHP_EOL;

        // Body
        $content .= $this->writeContent();

        $content .= '}';

        return $content;
    }

    /**
     * Get content data
     *
     * @return string
     */
    private function writeContent()
    {
        $content = '';

        $sections = $this->getPhpWord()->getSections();
        foreach ($sections as $section) {
            $writer = new Container($this, $section);
            $content .= $writer->write();
        }

        return $content;
    }

    /**
     * Populate font and color table group
     */
    private function populateTableGroups()
    {
        $phpWord = $this->getPhpWord();
        $this->fontTable[] = Settings::getDefaultFontName();

        // Search font in styles
        $styles = Style::getStyles();
        foreach ($styles as $style) {
            $this->pushTableGroupItems($style);
        }

        // Search font in elements
        $sections = $phpWord->getSections();
        foreach ($sections as $section) {
            $elements = $section->getElements();
            foreach ($elements as $element) {
                if (method_exists($element, 'getFontStyle')) {
                    $this->pushTableGroupItems($element->getFontStyle());
                }
            }
        }
    }

    /**
     * Push font and color table group items
     *
     * @param \PhpOffice\PhpWord\Style\AbstractStyle
     */
    private function pushTableGroupItems($style)
    {
        $defaultFont = Settings::getDefaultFontName();
        $defaultColor = Settings::DEFAULT_FONT_COLOR;
        if ($style instanceof Font) {
            $this->pushTableGroupItem($this->fontTable, $style->getName(), $defaultFont);
            $this->pushTableGroupItem($this->colorTable, $style->getColor(), $defaultColor);
            $this->pushTableGroupItem($this->colorTable, $style->getFgColor(), $defaultColor);
        }
    }

    /**
     * Push individual font and color into corresponding table group
     *
     * @param array $tableGroup
     * @param string $item
     * @param string $default
     */
    private function pushTableGroupItem(&$tableGroup, $item, $default)
    {
        if (!in_array($item, $tableGroup) && $item !== null && $item != $default) {
            $tableGroup[] = $item;
        }
    }
}
