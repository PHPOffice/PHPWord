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

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Element\AbstractContainer as ContainerElement;

/**
 * Container element HTML writer
 *
 * @since 0.11.0
 */
class Container extends AbstractElement
{
    /**
     * Namespace; Can't use __NAMESPACE__ in inherited class (RTF)
     *
     * @var string
     */
    protected $namespace = 'PhpOffice\\PhpWord\\Writer\\HTML\\Element';

    /**
     * Write container
     *
     * @return string
     */
    public function write()
    {
        $container = $this->element;
        if (!$container instanceof ContainerElement) {
            return '';
        }
        $containerClass = substr(get_class($container), strrpos(get_class($container), '\\') + 1);
        $withoutP = in_array($containerClass, array('TextRun', 'Footnote', 'Endnote'));
        $content = '';

        $tabsIdx = -1;
        $tabsCfg = array();
        if ($withoutP) {
            $paragraphStyle = $container->getParagraphStyle();
            if (is_object($paragraphStyle)) {
                $tabsCfg = $paragraphStyle->getTabs();
            }
        }

        $currentFontStyle = array();
        $currentColumnIdx = -1;
        $elementsContent = array();
        $prevColumnSize = 0;
        if ($tabsCfg) {
            $elementsContent[] = '<table class="tabstops"><tr>';
            $elementsContent[] = null;
            $currentColumnIdx = count($elementsContent) - 1;
        }

        $tabIdxs = array();
        $elements = $container->getElements();
        foreach ($elements as $element) {
            $elementClass = get_class($element);
            $writerClass = str_replace('PhpOffice\\PhpWord\\Element', $this->namespace, $elementClass);
            if (class_exists($writerClass)) {
                /** @var \PhpOffice\PhpWord\Writer\HTML\Element\AbstractElement $writer Type hint */
                $writer = new $writerClass($this->parentWriter, $element, $withoutP);
                if ($tabsCfg && (count($tabsCfg) > $tabsIdx) && ($writerClass == $this->namespace . '\\Text') && ($element->getText() == "\t")) {
                    $this->SetupTabStopElement($elementsContent, $currentColumnIdx, $currentFontStyle, $tabsCfg, $tabsIdx, $prevColumnSize);

                    // setup new tabs start
                    $currentFontStyle = array();
                    $elementsContent[] = null;
                    $currentColumnIdx = count($elementsContent) - 1;
                } else {
                    $elementsContent[] = $writer->write();
                    if (method_exists($element, 'getFontStyle') && ($fontStyle = $element->getFontStyle())) {
                        $currentFontStyle = array_merge_recursive($currentFontStyle, $fontStyle->getStyleValues());
                    }
                }
            }
        }

        if ($tabsCfg) {
            $this->SetupTabStopElement($elementsContent, $currentColumnIdx, $currentFontStyle, $tabsCfg, $tabsIdx, $prevColumnSize);
            if (is_null($elementsContent[$currentColumnIdx])) {
                $elementsContent[$currentColumnIdx] = ($currentColumnIdx > 1 ? '</td>' : '') . '<td class="tabstop">';
            }
            $elementsContent[] = '</td></tr></table>';
        }

        $content .= implode('', $elementsContent);

        return $content;
    }

    private function SetupTabStopElement(&$elementsContent, $currentColumnIdx, $currentFontStyle, $tabsCfg, &$tabsIdx, &$prevColumnSize)
    {
        $columnHtml = implode('', array_slice($elementsContent, $currentColumnIdx + 1));
        $columnText = preg_replace("/\s+/", ' ', strip_tags($columnHtml));

        $fontSize = null;
        if (isset($currentFontStyle['basic']['size'])) {
            $fontSize = $currentFontStyle['basic']['size'];
            if (is_array($fontSize)) {
                $fontSize = max($fontSize);
            }
        }
        if (!$fontSize) {
            $fontSize = 10;
        } // default value

        if (isset($currentFontStyle['style']['bold']) && $currentFontStyle['style']['bold']) {
            if (!is_array($currentFontStyle['style']['bold']) || in_array(true, $currentFontStyle['style']['bold'])) {
                $fontSize *= 1.2;
            }
        }

        $textWidthPt = strlen($columnText) * $fontSize * 0.35; // TODO: make better calculation size of text
        $textWidthTwip = $textWidthPt * 20;
        $idx = $tabsIdx + 1;
        while ($idx < count($tabsCfg)) {
            if (($tabsCfg[$idx]['pos'] - $prevColumnSize) > $textWidthTwip) {
                $tabsIdx = $idx;
                $size = ($tabsCfg[$idx]['pos'] - $prevColumnSize) / 20;
                $elementsContent[$currentColumnIdx] = ($currentColumnIdx > 1 ? '</td>' : '')
                                                        . '<td class="tabstop" style="width:' . $size . 'pt;">';
                $prevColumnSize = $tabsCfg[$idx]['pos'];
                break;
            }
            $idx++;
        }
    }
}
