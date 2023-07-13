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

namespace PhpOffice\PhpWord\Writer\HTML\Part;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Writer\HTML\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\HTML\Style\Generic as GenericStyleWriter;
use PhpOffice\PhpWord\Writer\HTML\Style\Paragraph as ParagraphStyleWriter;

/**
 * RTF head part writer.
 *
 * @since 0.11.0
 */
class Head extends AbstractPart
{
    /**
     * Write part.
     *
     * @return string
     */
    public function write()
    {
        $docProps = $this->getParentWriter()->getPhpWord()->getDocInfo();
        $propertiesMapping = [
            'creator' => 'author',
            'title' => '',
            'description' => '',
            'subject' => '',
            'keywords' => '',
            'category' => '',
            'company' => '',
            'manager' => '',
        ];
        $title = $docProps->getTitle();
        $title = ($title != '') ? $title : 'PHPWord';

        $content = '';

        $content .= '<head>' . PHP_EOL;
        $content .= '<meta charset="UTF-8" />' . PHP_EOL;
        $content .= '<title>' . $title . '</title>' . PHP_EOL;
        foreach ($propertiesMapping as $key => $value) {
            $value = ($value == '') ? $key : $value;
            $method = 'get' . $key;
            if ($docProps->$method() != '') {
                $content .= '<meta name="' . $value . '"'
                          . ' content="' . (Settings::isOutputEscapingEnabled() ? $this->escaper->escapeHtmlAttr($docProps->$method()) : $docProps->$method()) . '"'
                          . ' />' . PHP_EOL;
            }
        }
        $content .= $this->writeStyles();
        $content .= '</head>' . PHP_EOL;

        return $content;
    }

    /**
     * Get styles.
     *
     * @return string
     */
    private function writeStyles()
    {
        $css = '<style>' . PHP_EOL;

        // Default styles
        $defaultStyles = [
            '*' => [
                'font-family' => Settings::getDefaultFontName(),
                'font-size' => Settings::getDefaultFontSize() . 'pt',
            ],
            'a.NoteRef' => [
                'text-decoration' => 'none',
            ],
            'hr' => [
                'height' => '1px',
                'padding' => '0',
                'margin' => '1em 0',
                'border' => '0',
                'border-top' => '1px solid #CCC',
            ],
            'table' => [
                'border' => '1px solid black',
                'border-spacing' => '0px',
                'width ' => '100%',
            ],
            'td' => [
                'border' => '1px solid black',
            ],
        ];
        foreach ($defaultStyles as $selector => $style) {
            $styleWriter = new GenericStyleWriter($style);
            $css .= $selector . ' {' . $styleWriter->write() . '}' . PHP_EOL;
        }

        // Custom styles
        $customStyles = Style::getStyles();
        if (is_array($customStyles)) {
            foreach ($customStyles as $name => $style) {
                if ($style instanceof Font) {
                    $styleWriter = new FontStyleWriter($style);
                    if ($style->getStyleType() == 'title') {
                        $name = str_replace('Heading_', 'h', $name);
                    } else {
                        $name = '.' . $name;
                    }
                    $css .= "{$name} {" . $styleWriter->write() . '}' . PHP_EOL;
                } elseif ($style instanceof Paragraph) {
                    $styleWriter = new ParagraphStyleWriter($style);
                    $name = '.' . $name;
                    $css .= "{$name} {" . $styleWriter->write() . '}' . PHP_EOL;
                }
            }
        }
        $css .= '</style>' . PHP_EOL;

        return $css;
    }
}
