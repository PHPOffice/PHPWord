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
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\RTF\Part;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Numbering;
use PhpOffice\PhpWord\Style\Table;

/**
 * RTF header part writer.
 *
 * - Character set
 * - Font table
 * - File table (not supported yet)
 * - Color table
 * - Style sheet (not supported yet)
 * - List table (not supported yet)
 *
 * @since 0.11.0
 * @see  http://www.biblioscape.com/rtf15_spec.htm#Heading6
 */
class Header extends AbstractPart
{
    /**
     * Font table.
     *
     * @var array
     */
    private $fontTable = [];

    /**
     * Color table.
     *
     * @var array
     */
    private $colorTable = [];

    /**
     * List table.
     *
     * @var array
     */
    private $listTable = [];

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
     * Get list table.
     *
     * @return array
     */
    public function getListTable()
    {
        return $this->listTable;
    }

    /**
     * Write part.
     *
     * @return string
     */
    public function write()
    {
        $this->registerHeader();

        $content = '';

        $content .= $this->writeCharset();
        $content .= $this->writeDefaults();
        $content .= $this->writeFontTable();
        $content .= $this->writeColorTable();
        $content .= $this->writeListTable();
        $content .= $this->writeGenerator();
        $content .= PHP_EOL;

        return $content;
    }

    /**
     * Write character set.
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
     * Write header defaults.
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
     * Write font table.
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
     * Write color table.
     *
     * @return string
     */
    private function writeColorTable()
    {
        $content = '';

        $content .= '{';
        $content .= '\colortbl;';
        foreach ($this->colorTable as $color) {
            [$red, $green, $blue] = Converter::htmlToRgb($color);
            $content .= "\\red{$red}\\green{$green}\\blue{$blue};";
        }
        $content .= '}';
        $content .= PHP_EOL;

        return $content;
    }

    /**
     * Write list table.
     *
     * @return string
     */
    private function writeListTable()
    {
        $content = '';

        $listType = [
            'singleLevel' => '\listsimple1',
            'multilevel' => '\listsimple0',
            'hybridMultilevel' => '\listhybrid',
        ];

        $numberType = [
            \PhpOffice\PhpWord\SimpleType\NumberFormat::DECIMAL => '0',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::UPPER_ROMAN => '1',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::LOWER_ROMAN => '2',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::UPPER_LETTER => '3',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::LOWER_LETTER => '4',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::ORDINAL => '5',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::CARDINAL_TEXT => '6',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::ORDINAL_TEXT => '7',
            /* \PhpOffice\PhpWord\SimpleType\NumberFormat::HEX => 'hex',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::CHICAGO => 'chicago',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::IDEOGRAPH_DIGITAL => 'ideographDigital',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::JAPANESE_COUNTING => 'japaneseCounting', */
            \PhpOffice\PhpWord\SimpleType\NumberFormat::AIUEO => '12',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::IROHA => '13',
            /* \PhpOffice\PhpWord\SimpleType\NumberFormat::DECIMAL_FULL_WIDTH => 'decimalFullWidth',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::DECIMAL_HALF_WIDTH => 'decimalHalfWidth',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::JAPANESE_LEGAL => 'japaneseLegal',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::JAPANESE_DIGITAL_TEN_THOUSAND => 'japaneseDigitalTenThousand',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::DECIMAL_ENCLOSED_CIRCLE => 'decimalEnclosedCircle',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::DECIMAL_FULL_WIDTH2 => 'decimalFullWidth2', */
            \PhpOffice\PhpWord\SimpleType\NumberFormat::AIUEO_FULL_WIDTH => '20',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::IROHA_FULL_WIDTH => '21',
            // \PhpOffice\PhpWord\SimpleType\NumberFormat::DECIMAL_ZERO => 'decimalZero',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::BULLET => '23',
            /* \PhpOffice\PhpWord\SimpleType\NumberFormat::GANADA => 'ganada',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::CHOSUNG => 'chosung',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::DECIMAL_ENCLOSED_FULL_STOP => 'decimalEnclosedFullstop',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::DECIMAL_ENCLOSED_PAREN => 'decimalEnclosedParen',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::DECIMAL_ENCLOSED_CIRCLE_CHINESE => 'decimalEnclosedCircleChinese',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::IDEOGRAPHENCLOSEDCIRCLE => 'ideographEnclosedCircle',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::IDEOGRAPH_TRADITIONAL => 'ideographTraditional',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::IDEOGRAPH_ZODIAC => 'ideographZodiac',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::IDEOGRAPH_ZODIAC_TRADITIONAL => 'ideographZodiacTraditional',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::TAIWANESE_COUNTING => 'taiwaneseCounting',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::IDEOGRAPH_LEGAL_TRADITIONAL => 'ideographLegalTraditional',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::TAIWANESE_COUNTING_THOUSAND => 'taiwaneseCountingThousand',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::TAIWANESE_DIGITAL => 'taiwaneseDigital',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::CHINESE_COUNTING => 'chineseCounting',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::CHINESE_LEGAL_SIMPLIFIED => 'chineseLegalSimplified',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::CHINESE_COUNTING_THOUSAND => 'chineseCountingThousand',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::KOREAN_DIGITAL => 'koreanDigital',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::KOREAN_COUNTING => 'koreanCounting',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::KOREAN_LEGAL => 'koreanLegal',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::KOREAN_DIGITAL2 => 'koreanDigital2', */
            \PhpOffice\PhpWord\SimpleType\NumberFormat::VIETNAMESE_COUNTING => '56',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::RUSSIAN_LOWER => '58',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::RUSSIAN_UPPER => '59',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::NONE => '255',
            /* \PhpOffice\PhpWord\SimpleType\NumberFormat::NUMBER_IN_DASH => 'numberInDash',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::HEBREW1 => 'hebrew1',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::HEBREW2 => 'hebrew2',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::ARABIC_ALPHA => 'arabicAlpha', */
            \PhpOffice\PhpWord\SimpleType\NumberFormat::ARABIC_ABJAD => '48',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::HINDI_VOWELS => '49',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::HINDI_CONSONANTS => '50',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::HINDI_NUMBERS => '51',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::HINDI_COUNTING => '52',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::THAI_LETTERS => '53',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::THAI_NUMBERS => '54',
            \PhpOffice\PhpWord\SimpleType\NumberFormat::THAI_COUNTING => '55',
        ];

        $listAlignment = [
            'left' => '0',
            'center' => '1',
            'right' => '2',
        ];

        $content .= '{';
        $content .= '\*\listtable' . PHP_EOL;

        foreach ($this->listTable as $list) {
            $content .= '{';
            $content .= '\list\listtemplateid' . $list['numId'];
            if (isset($listType[$list['type']])) {
                $content .= $listType[$list['type']];
            }
            $content .= PHP_EOL;
            foreach ($list['listItems'] as $listItem) {
                $content .= '{';
                $content .= '\listlevel';
                if (isset($numberType[$listItem['format']])) {
                    $content .= '\levelnfc' . $numberType[$listItem['format']];
                    $content .= '\levelnfcn' . $numberType[$listItem['format']];
                }
                if (isset($listAlignment[$listItem['alignment']])) {
                    $content .= '\leveljc' . $listAlignment[$listItem['alignment']];
                    $content .= '\leveljcn' . $listAlignment[$listItem['alignment']];
                }
                $content .= '\levelstartat' . $listItem['start'];
                if (isset($listItem['restart'])) {
                    $content .= '\levelnorestart' . $listItem['restart'];
                }

                // Level Text and Numbers
                $positions = [];
                $level = '';
                $strLength = '';
                if (strpos($listItem['text'], '%') !== false) {
                    $level = $this->lowerDigitsByOne(str_replace('%', '\\\'0', $listItem['text']));
                    $levelNumbers = (string) preg_replace('/\d/', 'X', str_replace('%', '', $listItem['text']));
                    $offset = 0;
                    while (($pos = strpos($levelNumbers, 'X', $offset)) !== false) {
                        $positions[] = $pos;
                        $offset = $pos + 1;
                    }
                    $strLength = (string) sprintf('%02d', strlen($levelNumbers));
                } else {
                    $level = '\\\'' . (string) strtoupper(dechex(ord(iconv('UTF-8', 'UCS-2', $listItem['text']))));
                    $strLength = '01';
                }

                $content .= '{';
                $content .= '\leveltext \\\'' . $strLength . $level;
                $content .= ' ;}';
                $content .= '{';
                $content .= '\levelnumbers ';
                foreach ($positions as $position) {
                    ++$position;
                    $content .= '\\\'0' . $position;
                }
                $content .= ';}';

                // Font settings for Level Numbers
                if (isset($listItem['font']) && !empty($listItem['font'])) {
                    $fontKey = array_search($listItem['font'], $this->fontTable);
                    if ($fontKey !== false) {
                        $content .= '\f' . $fontKey;
                    }
                }

                // Tabs, Hanging, and First Line
                $content .= '\levelfollow' . '0';
                $content .= '\jclisttab';
                $content .= '\tx' . $listItem['tabPos'];
                $hanging = 0 - $listItem['hanging'];
                $content .= '\fi' . $hanging;
                $content .= '\li' . $listItem['left'];
                $content .= '\lin' . $listItem['left'];
                $content .= '}';
                $content .= PHP_EOL;
            }
            $content .= '\listid' . $list['numId'] . '}';
            $content .= PHP_EOL;
        }
        $content .= '}';
        $content .= PHP_EOL . PHP_EOL;

        $content .= '{';
        $content .= '\*\listoverridetable' . PHP_EOL;
        foreach ($this->listTable as $list) {
            $content .= '{';
            $content .= '\listoverride\listid' . $list['numId'];
            $content .= '\listoverridecount0\ls' . $list['numId'];
            $content .= '}';
            $content .= PHP_EOL;
        }
        $content .= '}';
        $content .= PHP_EOL . PHP_EOL;

        return $content;
    }

    /**
     * Write.
     *
     * @return string
     */
    private function writeGenerator()
    {
        $content = '';

        $content .= '{\*\generator PHPWord;}'; // Set the generator
        $content .= PHP_EOL;

        return $content;
    }

    /**
     * Register all fonts, colors, and lists in both named and inline styles to appropriate header table.
     */
    private function registerHeader(): void
    {
        $phpWord = $this->getParentWriter()->getPhpWord();
        $this->fontTable[] = Settings::getDefaultFontName();

        // Search named styles
        $styles = Style::getStyles();
        foreach ($styles as $style) {
            $this->registerHeaderItems($style);
        }

        // Search inline styles
        $sections = $phpWord->getSections();
        foreach ($sections as $section) {
            $elements = $section->getElements();
            $this->registerBorderColor($section->getStyle());
            foreach ($elements as $element) {
                if (method_exists($element, 'getFontStyle')) {
                    $style = $element->getFontStyle();
                    $this->registerHeaderItems($style);
                }
            }
        }
    }

    /**
     * Register border colors.
     *
     * @param Style\Border $style
     */
    private function registerBorderColor($style): void
    {
        $colors = $style->getBorderColor();
        foreach ($colors as $color) {
            if ($color !== null) {
                $this->registerTableItem($this->colorTable, $color);
            }
        }
    }

    /**
     * Register fonts, colors, and lists.
     *
     * @param Style\AbstractStyle $style
     */
    private function registerHeaderItems($style): void
    {
        $defaultFont = Settings::getDefaultFontName();
        $defaultColor = Settings::DEFAULT_FONT_COLOR;

        if ($style instanceof Font) {
            $this->registerTableItem($this->fontTable, $style->getName(), $defaultFont);
            $this->registerTableItem($this->colorTable, $style->getColor(), $defaultColor);
            $this->registerTableItem($this->colorTable, $style->getFgColor(), $defaultColor);

            return;
        }
        if ($style instanceof Table) {
            $this->registerTableItem($this->colorTable, $style->getBorderTopColor(), $defaultColor);
            $this->registerTableItem($this->colorTable, $style->getBorderRightColor(), $defaultColor);
            $this->registerTableItem($this->colorTable, $style->getBorderLeftColor(), $defaultColor);
            $this->registerTableItem($this->colorTable, $style->getBorderBottomColor(), $defaultColor);
        }
        if ($style instanceof Numbering) {
            $this->registerList($this->listTable, $style, $defaultFont);
        }
    }

    /**
     * Register individual font and color.
     *
     * @param array &$table
     * @param string $value
     * @param string $default
     */
    private function registerTableItem(&$table, $value, $default = null): void
    {
        if (in_array($value, $table) === false && $value !== null && $value != $default) {
            $table[] = $value;
        }
    }

    /**
     * Register individual font and color.
     *
     * @param array &$table
     * @param string $value
     * @param string $default
     */
    private function registerTableItem(&$table, $value, $default = null): void
    {
        if (in_array($value, $table) === false && $value !== null && $value != $default) {
            $table[] = $value;
        }
    }

    /**
     * Register lists and fonts within lists.
     *
     * @param array &$table
     * @param Numbering $style
     * @param string $defaultFont
     */
    private function registerList(&$table, $style, $defaultFont = null): void
    {
        $listItems = [];

        $levels = $style->getLevels();
        foreach ($levels as $level) {
            $this->registerTableItem($this->fontTable, $level->getFont(), $defaultFont);

            $listItem = [
                'level' => $level->getLevel(),
                'start' => $level->getStart(),
                'restart' => $level->getRestart(),
                'format' => $level->getFormat(),
                'pStyle' => $level->getPStyle(),
                'suffix' => $level->getSuffix(),
                'text' => $level->getText(),
                'alignment' => $level->getAlignment(),
                'left' => $level->getLeft(),
                'hanging' => $level->getHanging(),
                'tabPos' => $level->getTabPos(),
                'font' => $level->getFont(),
                'hint' => $level->getHint(),
            ];
            array_push($listItems, $listItem);
        }

        $list = [
            'numId' => $style->getNumId(),
            'type' => $style->getType(),
            'listItems' => $listItems,
        ];
        $table[] = $list;
    }

    /**
     * NumberingLevel->getText() returns levels a step higher than expected in RTF \leveltext, (1-9) instead of (0-8).
     * Thus all the digits need to be reduced by 1.
     *
     * @param string $string
     */
    private function lowerDigitsByOne($string): string
    {
        return preg_replace_callback('/\d/', function ($matches) {
            $digit = (int) $matches[0];

            return ($digit > 0) ? (string) ($digit - 1) : (string) $digit;
        }, $string);
    }
}
