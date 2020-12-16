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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Part;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\Writer\HTML\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\HTML\Style\Generic as GenericStyleWriter;
use PhpOffice\PhpWord\Writer\HTML\Style\Paragraph as ParagraphStyleWriter;
use PhpOffice\PhpWord\Writer\HTML\Style\Table as TableStyleWriter;

/**
 * RTF head part writer
 *
 * @since 0.11.0
 */
class Head extends AbstractPart
{
    /**
     * Write part
     *
     * @return string
     */
    public function write()
    {
        $docProps = $this->getParentWriter()->getPhpWord()->getDocInfo();
        $propertiesMapping = array(
            'creator'     => 'author',
            'title'       => '',
            'description' => '',
            'subject'     => '',
            'keywords'    => '',
            'category'    => '',
            'company'     => '',
            'manager'     => '',
        );
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
     * Get styles
     *
     * @return string
     */
    private function writeStyles()
    {
        $css = '<style>' . PHP_EOL;

        // Default styles
        $defaultStyles = array(
            '*' => array(
                'font-family' => Settings::getDefaultFontName(),
                'font-size'   => Settings::getDefaultFontSize() . 'pt',
            ),
            'a.NoteRef' => array(
                'text-decoration' => 'none',
            ),
            'hr' => array(
                'height'     => '1px',
                'padding'    => '0',
                'margin'     => '1em 0',
                'border'     => '0',
                'border-top' => '1px solid #ccc',
            ),
            'table' => array(
                'border'         => '0',
                'border-collapse'=> 'collapse',
                'border-spacing' => '0',
                'width '         => '100%',
            ),
            'td' => array(
                'border'        => '0',
            ),
            'p' => array(
                'margin-top'	   => '0',
                'margin-bottom' => '0',
            ),
            'div.paragraph' => array(
                'margin'    	   => '0',
                'margin-bottom' => '0',
            ),
            'table.tabstops'=> array(
                'width'         => 'auto',
                'table-layout'  => 'fixed',
                'border'        => '0',
            ),
            'td.tabstop'=> array(
                'white-space'   => 'nowrap',
                'border'        => '0',
            ),
        );
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
                } elseif ($style instanceof Table) {
                    $styleWriter = new TableStyleWriter($style);
                    $name = '.' . $name;
                    $css .= "{$name} {" . $styleWriter->write() . '}' . PHP_EOL;
                }
            }
        }
        $css .= '</style>' . PHP_EOL;

        return $css;
    }
}
