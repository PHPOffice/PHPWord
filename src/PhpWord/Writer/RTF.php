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
     * Color register
     *
     * @var array
     */
    private $colorTable;

    /**
     * Font register
     *
     * @var array
     */
    private $fontTable;

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
        // Assign PhpWord
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
        if (is_null($this->phpWord)) {
            throw new Exception('PhpWord object unassigned.');
        }

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
     * Get color table
     */
    public function getColorTable()
    {
        return $this->colorTable;
    }

    /**
     * Get font table
     */
    public function getFontTable()
    {
        return $this->fontTable;
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
        $this->fontTable = $this->populateFontTable();
        $this->colorTable = $this->populateColorTable();

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
        $content .= '\viewkind4'; // Set the view mode of the document
        $content .= '\uc1'; // Set the numberof bytes that follows a unicode character
        $content .= '\pard'; // Resets to default paragraph properties.
        $content .= '\nowidctlpar'; // No widow/orphan control
        $content .= '\lang1036'; // Applies a language to a text run (1036 : French (France))
        $content .= '\kerning1'; // Point size (in half-points) above which to kern character pairs
        $content .= '\fs' . (PhpWord::DEFAULT_FONT_SIZE * 2); // Set the font size in half-points
        $content .= PHP_EOL;

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
     * Get all fonts
     *
     * @return array
     */
    private function populateFontTable()
    {
        $phpWord = $this->phpWord;

        $arrFonts = array();
        // Default font : PhpWord::DEFAULT_FONT_NAME
        $arrFonts[] = PhpWord::DEFAULT_FONT_NAME;
        // PhpWord object : $this->phpWord

        // Browse styles
        $styles = Style::getStyles();
        if (count($styles) > 0) {
            foreach ($styles as $style) {
                // Font
                if ($style instanceof Font) {
                    if (in_array($style->getName(), $arrFonts) == false) {
                        $arrFonts[] = $style->getName();
                    }
                }
            }
        }

        // Search all fonts used
        $sections = $phpWord->getSections();
        $countSections = count($sections);
        if ($countSections > 0) {
            $pSection = 0;

            foreach ($sections as $section) {
                $pSection++;
                $elements = $section->getElements();

                foreach ($elements as $element) {
                    if (method_exists($element, 'getFontStyle')) {
                        $fontStyle = $element->getFontStyle();

                        if ($fontStyle instanceof Font) {
                            if (in_array($fontStyle->getName(), $arrFonts) == false) {
                                $arrFonts[] = $fontStyle->getName();
                            }
                        }
                    }
                }
            }
        }

        return $arrFonts;
    }

    /**
     * Get all colors
     *
     * @return array
     */
    private function populateColorTable()
    {
        $phpWord = $this->phpWord;
        $defaultFontColor = PhpWord::DEFAULT_FONT_COLOR;

        $arrColors = array();
        // PhpWord object : $this->phpWord

        // Browse styles
        $styles = Style::getStyles();
        if (count($styles) > 0) {
            foreach ($styles as $style) {
                // Font
                if ($style instanceof Font) {
                    $color = $style->getColor();
                    $fgcolor = $style->getFgColor();
                    if (!in_array($color, $arrColors) && $color != $defaultFontColor && !empty($color)) {
                        $arrColors[] = $color;
                    }
                    if (!in_array($fgcolor, $arrColors) && $fgcolor != $defaultFontColor && !empty($fgcolor)) {
                        $arrColors[] = $fgcolor;
                    }
                }
            }
        }

        // Search all fonts used
        $sections = $phpWord->getSections();
        $countSections = count($sections);
        if ($countSections > 0) {
            $pSection = 0;

            foreach ($sections as $section) {
                $pSection++;
                $elements = $section->getElements();

                foreach ($elements as $element) {
                    if (method_exists($element, 'getFontStyle')) {
                        $fontStyle = $element->getFontStyle();

                        if ($fontStyle instanceof Font) {
                            if (in_array($fontStyle->getColor(), $arrColors) == false) {
                                $arrColors[] = $fontStyle->getColor();
                            }
                            if (in_array($fontStyle->getFgColor(), $arrColors) == false) {
                                $arrColors[] = $fontStyle->getFgColor();
                            }
                        }
                    }
                }
            }
        }

        return $arrColors;
    }
}
