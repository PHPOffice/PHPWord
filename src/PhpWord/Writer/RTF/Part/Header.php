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
 * @copyright   2010-2015 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\RTF\Part;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;

/**
 * RTF header part writer
 *
 * - Character set
 * - Font table
 * - File table (not supported yet)
 * - Color table
 * - Style sheet (not supported yet)
 * - List table (not supported yet)
 *
 * @since 0.11.0
 * @link http://www.biblioscape.com/rtf15_spec.htm#Heading6
 */
class Header extends AbstractPart
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
     * Get font table.
     *
     * @return array
     */
    public function getFontTable()
    {
        return $this->fontTable;
    }

    /**
     * Get color table.
     *
     * @return array
     */
    public function getColorTable()
    {
        return $this->colorTable;
    }

    /**
     * Write part
     *
     * @return string
     */
    public function write()
    {
        $this->registerFont();

        $content = '';

        $content .= $this->writeCharset();
        $content .= $this->writeDefaults();
        $content .= $this->writeFontTable();
        $content .= $this->writeColorTable();
        $content .= $this->writeGenerator();
        $content .= PHP_EOL;

        return $content;
    }

    /**
     * Write character set
     *
     * @return string
     */
    private function writeCharset()
    {
        $content = '';

        $content .= '\ansi';
        $content .= '\ansicpg1252';
        $content .= PHP_EOL;

        return $content;
    }

    /**
     * Write header defaults
     *
     * @return string
     */
    private function writeDefaults()
    {
        $content = '';

        $content .= '\deff0';
        $content .= PHP_EOL;

        return $content;
    }

    /**
     * Write font table
     *
     * @return string
     */
    private function writeFontTable()
    {
        $content = '';

        $content .= '{';
        $content .= '\fonttbl';
        foreach ($this->fontTable as $index => $font) {
            $content .= "{\\f{$index}\\fnil\\fcharset0 {$font};}";
        }
        $content .= '}';
        $content .= PHP_EOL;

        return $content;
    }

    /**
     * Write color table
     *
     * @return string
     */
    private function writeColorTable()
    {
        $content = '';

        $content .= '{';
        $content .= '\colortbl;';
        foreach ($this->colorTable as $color) {
            list($red, $green, $blue) = Converter::htmlToRgb($color);
            $content .= "\\red{$red}\\green{$green}\\blue{$blue};";
        }
        $content .= '}';
        $content .= PHP_EOL;

        return $content;
    }

    /**
     * Write
     *
     * @return string
     */
    private function writeGenerator()
    {
        $content = '';

        $content .= '{\*\generator PhpWord;}'; // Set the generator
        $content .= PHP_EOL;

        return $content;
    }

    /**
     * Register all fonts and colors in both named and inline styles to appropriate header table.
     *
     * @return void
     */
    private function registerFont()
    {
        $phpWord = $this->getParentWriter()->getPhpWord();
        $this->fontTable[] = Settings::getDefaultFontName();

        // Search named styles
        $styles = Style::getStyles();
        foreach ($styles as $style) {
            $this->registerFontItems($style);
        }

        // Search inline styles
        $sections = $phpWord->getSections();
        foreach ($sections as $section) {
            $elements = $section->getElements();
            $this->registerBorderColor($section->getStyle());
            foreach ($elements as $element) {
                if (method_exists($element, 'getFontStyle')) {
                    $style = $element->getFontStyle();
                    $this->registerFontItems($style);
                }
            }
        }
    }

    /**
     * Register border colors.
     *
     * @param \PhpOffice\PhpWord\Style\Border $style
     * @return void
     */
    private function registerBorderColor($style)
    {
        $colors = $style->getBorderColor();
        foreach ($colors as $color) {
            if ($color !== null) {
                $this->registerTableItem($this->colorTable, $color);
            }
        }
    }

    /**
     * Register fonts and colors.
     *
     * @param \PhpOffice\PhpWord\Style\AbstractStyle $style
     * @return void
     */
    private function registerFontItems($style)
    {
        $defaultFont = Settings::getDefaultFontName();
        $defaultColor = Settings::DEFAULT_FONT_COLOR;

        if ($style instanceof Font) {
            $this->registerTableItem($this->fontTable, $style->getName(), $defaultFont);
            $this->registerTableItem($this->colorTable, $style->getColor(), $defaultColor);
            $this->registerTableItem($this->colorTable, $style->getFgColor(), $defaultColor);
        }
    }

    /**
     * Register individual font and color.
     *
     * @param array &$table
     * @param string $value
     * @param string $default
     * @return void
     */
    private function registerTableItem(&$table, $value, $default = null)
    {
        if (in_array($value, $table) === false && $value !== null && $value != $default) {
            $table[] = $value;
        }
    }
}
