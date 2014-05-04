<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\Drawing;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Writer\RTF\Element\Element as ElementWriter;

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
     * @param string $pFilename
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function save($pFilename = null)
    {
        if (!is_null($this->phpWord)) {
            $pFilename = $this->getTempFile($pFilename);

            $hFile = fopen($pFilename, 'w');
            if ($hFile !== false) {
                fwrite($hFile, $this->writeDocument());
                fclose($hFile);
            } else {
                throw new Exception("Can't open file");
            }
            $this->cleanupTempFile();
        } else {
            throw new Exception("PhpWord object unassigned.");
        }
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
        $sRTFContent = '{\rtf1';
        $sRTFContent .= '\ansi\ansicpg1252'; // Set the default font (the first one)
        $sRTFContent .= '\deff0'; // Set the default tab size (720 twips)
        $sRTFContent .= '\deftab720';
        $sRTFContent .= PHP_EOL;

        // Set the font tbl group
        $sRTFContent .= '{\fonttbl';
        foreach ($this->fontTable as $idx => $font) {
            $sRTFContent .= '{\f' . $idx . '\fnil\fcharset0 ' . $font . ';}';
        }
        $sRTFContent .= '}' . PHP_EOL;

        // Set the color tbl group
        $sRTFContent .= '{\colortbl ';
        foreach ($this->colorTable as $idx => $color) {
            $arrColor = Drawing::htmlToRGB($color);
            $sRTFContent .= ';\red' . $arrColor[0] . '\green' . $arrColor[1] . '\blue' . $arrColor[2] . '';
        }
        $sRTFContent .= ';}' . PHP_EOL;

        $sRTFContent .= '{\*\generator PhpWord;}' . PHP_EOL; // Set the generator
        $sRTFContent .= '\viewkind4'; // Set the view mode of the document
        $sRTFContent .= '\uc1'; // Set the numberof bytes that follows a unicode character
        $sRTFContent .= '\pard'; // Resets to default paragraph properties.
        $sRTFContent .= '\nowidctlpar'; // No widow/orphan control
        $sRTFContent .= '\lang1036'; // Applies a language to a text run (1036 : French (France))
        $sRTFContent .= '\kerning1'; // Point size (in half-points) above which to kern character pairs
        $sRTFContent .= '\fs' . (PhpWord::DEFAULT_FONT_SIZE * 2); // Set the font size in half-points
        $sRTFContent .= PHP_EOL;

        // Body
        $sRTFContent .= $this->writeContent();

        $sRTFContent .= '}';

        return $sRTFContent;
    }

    /**
     * Get content data
     *
     * @return string
     */
    private function writeContent()
    {
        $phpWord = $this->phpWord;
        $sRTFBody = '';

        $sections = $phpWord->getSections();
        $countSections = count($sections);
        $pSection = 0;

        if ($countSections > 0) {
            foreach ($sections as $section) {
                $pSection++;
                $elements = $section->getElements();
                foreach ($elements as $element) {
                    $elementWriter = new ElementWriter($this, $element);
                    $sRTFBody .= $elementWriter->write();
                }
            }
        }
        return $sRTFBody;
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
                    if ($element instanceof Text) {
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
                    if (in_array($color, $arrColors) == false && $color != PhpWord::DEFAULT_FONT_COLOR && !empty($color)) {
                        $arrColors[] = $color;
                    }
                    if (in_array($fgcolor, $arrColors) == false && $fgcolor != PhpWord::DEFAULT_FONT_COLOR && !empty($fgcolor)) {
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
                    if ($element instanceof Text) {
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
