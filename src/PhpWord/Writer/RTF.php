<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\Link;
use PhpOffice\PhpWord\Element\ListItem;
use PhpOffice\PhpWord\Element\Object;
use PhpOffice\PhpWord\Element\PageBreak;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Shared\Drawing;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\TOC;

/**
 * RTF writer
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
     * @param PhpWord $phpWord
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
     * @throws Exception
     */
    public function save($pFilename = null)
    {
        if (!is_null($this->phpWord)) {
            $pFilename = $this->getTempFile($pFilename);

            $hFile = fopen($pFilename, 'w') or die("can't open file");
            fwrite($hFile, $this->getData());
            fclose($hFile);

            $this->cleanupTempFile();
        } else {
            throw new Exception("PhpWord object unassigned.");
        }
    }

    /**
     * Get all data
     *
     * @return string
     */
    private function getData()
    {
        // PhpWord object : $this->phpWord
        $this->fontTable = $this->getDataFont();
        $this->colorTable = $this->getDataColor();

        $sRTFContent = '{\rtf1';
        // Set the default character set
        $sRTFContent .= '\ansi\ansicpg1252';
        // Set the default font (the first one)
        $sRTFContent .= '\deff0';
        // Set the default tab size (720 twips)
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
        // Set the generator
        $sRTFContent .= '{\*\generator PhpWord;}' . PHP_EOL;
        // Set the view mode of the document
        $sRTFContent .= '\viewkind4';
        // Set the numberof bytes that follows a unicode character
        $sRTFContent .= '\uc1';
        // Resets to default paragraph properties.
        $sRTFContent .= '\pard';
        // No widow/orphan control
        $sRTFContent .= '\nowidctlpar';
        // Applies a language to a text run (1036 : French (France))
        $sRTFContent .= '\lang1036';
        // Point size (in half-points) above which to kern character pairs
        $sRTFContent .= '\kerning1';
        // Set the font size in half-points
        $sRTFContent .= '\fs' . (PhpWord::DEFAULT_FONT_SIZE * 2);
        $sRTFContent .= PHP_EOL;
        // Body
        $sRTFContent .= $this->getDataContent();


        $sRTFContent .= '}';

        return $sRTFContent;
    }

    /**
     * Get all fonts
     *
     * @return array
     */
    private function getDataFont()
    {
        $phpWord = $this->phpWord;

        $arrFonts = array();
        // Default font : PhpWord::DEFAULT_FONT_NAME
        $arrFonts[] = PhpWord::DEFAULT_FONT_NAME;
        // PhpWord object : $this->phpWord

        // Browse styles
        $styles = Style::getStyles();
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
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
                        $fStyle = $element->getFontStyle();

                        if ($fStyle instanceof Font) {
                            if (in_array($fStyle->getName(), $arrFonts) == false) {
                                $arrFonts[] = $fStyle->getName();
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
    private function getDataColor()
    {
        $phpWord = $this->phpWord;

        $arrColors = array();
        // PhpWord object : $this->phpWord

        // Browse styles
        $styles = Style::getStyles();
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
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
                        $fStyle = $element->getFontStyle();

                        if ($fStyle instanceof Font) {
                            if (in_array($fStyle->getColor(), $arrColors) == false) {
                                $arrColors[] = $fStyle->getColor();
                            }
                            if (in_array($fStyle->getFgColor(), $arrColors) == false) {
                                $arrColors[] = $fStyle->getFgColor();
                            }
                        }
                    }
                }
            }
        }

        return $arrColors;
    }

    /**
     * Get content data
     *
     * @return string
     */
    private function getDataContent()
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
                    if ($element instanceof Text) {
                        $sRTFBody .= $this->getDataContentText($element);
                    } elseif ($element instanceof TextBreak) {
                        $sRTFBody .= $this->getDataContentTextBreak();
                    } elseif ($element instanceof TextRun) {
                        $sRTFBody .= $this->getDataContentTextRun($element);
                    } elseif ($element instanceof Link) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Link');
                    } elseif ($element instanceof Title) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Title');
                    } elseif ($element instanceof PageBreak) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Page Break');
                    } elseif ($element instanceof Table) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Table');
                    } elseif ($element instanceof ListItem) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('List Item');
                    } elseif ($element instanceof Image) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Image');
                    } elseif ($element instanceof Object) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Object');
                    } elseif ($element instanceof TOC) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('TOC');
                    } else {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Other');
                    }
                }
            }
        }
        return $sRTFBody;
    }

    /**
     * Get text element content
     *
     * @param boolean $withoutP
     * @return string
     */
    private function getDataContentText(Text $text, $withoutP = false)
    {
        $sRTFText = '';

        $styleFont = $text->getFontStyle();
        if (is_string($styleFont)) {
            $styleFont = Style::getStyle($styleFont);
        }

        $styleParagraph = $text->getParagraphStyle();
        if (is_string($styleParagraph)) {
            $styleParagraph = Style::getStyle($styleParagraph);
        }

        if ($styleParagraph && !$withoutP) {
            if ($this->lastParagraphStyle != $text->getParagraphStyle()) {
                $sRTFText .= '\pard\nowidctlpar';
                if ($styleParagraph->getSpaceAfter() != null) {
                    $sRTFText .= '\sa' . $styleParagraph->getSpaceAfter();
                }
                if ($styleParagraph->getAlign() != null) {
                    if ($styleParagraph->getAlign() == 'center') {
                        $sRTFText .= '\qc';
                    }
                }
                $this->lastParagraphStyle = $text->getParagraphStyle();
            } else {
                $this->lastParagraphStyle = '';
            }
        } else {
            $this->lastParagraphStyle = '';
        }

        if ($styleFont instanceof Font) {
            if ($styleFont->getColor() != null) {
                $idxColor = array_search($styleFont->getColor(), $this->colorTable);
                if ($idxColor !== false) {
                    $sRTFText .= '\cf' . ($idxColor + 1);
                }
            } else {
                $sRTFText .= '\cf0';
            }
            if ($styleFont->getName() != null) {
                $idxFont = array_search($styleFont->getName(), $this->fontTable);
                if ($idxFont !== false) {
                    $sRTFText .= '\f' . $idxFont;
                }
            } else {
                $sRTFText .= '\f0';
            }
            if ($styleFont->getBold()) {
                $sRTFText .= '\b';
            }
            if ($styleFont->getItalic()) {
                $sRTFText .= '\i';
            }
            if ($styleFont->getSize()) {
                $sRTFText .= '\fs' . ($styleFont->getSize() * 2);
            }
        }
        if ($this->lastParagraphStyle != '' || $styleFont) {
            $sRTFText .= ' ';
        }
        $sRTFText .= $text->getText();

        if ($styleFont instanceof Font) {
            $sRTFText .= '\cf0';
            $sRTFText .= '\f0';

            if ($styleFont->getBold()) {
                $sRTFText .= '\b0';
            }
            if ($styleFont->getItalic()) {
                $sRTFText .= '\i0';
            }
            if ($styleFont->getSize()) {
                $sRTFText .= '\fs' . (PhpWord::DEFAULT_FONT_SIZE * 2);
            }
        }

        if (!$withoutP) {
            $sRTFText .= '\par' . PHP_EOL;
        }
        return $sRTFText;
    }

    /**
     * Get textrun content
     *
     * @return string
     */
    private function getDataContentTextRun(TextRun $textrun)
    {
        $sRTFText = '';
        $elements = $textrun->getElements();
        if (count($elements) > 0) {
            $sRTFText .= '\pard\nowidctlpar' . PHP_EOL;
            foreach ($elements as $element) {
                if ($element instanceof Text) {
                    $sRTFText .= '{';
                    $sRTFText .= $this->getDataContentText($element, true);
                    $sRTFText .= '}' . PHP_EOL;
                }
            }
            $sRTFText .= '\par' . PHP_EOL;
        }
        return $sRTFText;
    }

    /**
     * Get text break content
     *
     * @return string
     */
    private function getDataContentTextBreak()
    {
        $this->lastParagraphStyle = '';

        return '\par' . PHP_EOL;
    }

    /**
     * Get unsupported element content
     *
     * @param   string  $element
     */
    private function getDataContentUnsupportedElement($element)
    {
        $sRTFText = '';
        $sRTFText .= '\pard\nowidctlpar' . PHP_EOL;
        $sRTFText .= "{$element}";
        $sRTFText .= '\par' . PHP_EOL;

        return $sRTFText;
    }
}
