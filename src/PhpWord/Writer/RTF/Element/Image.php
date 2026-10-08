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

namespace PhpOffice\PhpWord\Writer\RTF\Element;

use PhpOffice\PhpWord\Element\Image as ImageElement;
use PhpOffice\PhpWord\Shared\Converter;

/**
 * Image element RTF writer.
 *
 * @since 0.11.0
 */
class Image extends AbstractElement
{
    /**
     * Write element.
     *
     * @return string
     */
    public function write()
    {
        $element = $this->element;
        if (!$element instanceof ImageElement) {
            return '';
        }

        $this->getStyles();
        $style = $this->element->getStyle();

        $content = '';
        $content .= $this->writeOpening();
        $content .= '{\*\shppict {\pict';
        $content .= '\pngblip\picscalex100\picscaley100';
        $content .= '\picwgoal' . round(Converter::pixelToTwip($style->getWidth()));
        $content .= '\pichgoal' . round(Converter::pixelToTwip($style->getHeight()));
        $content .= PHP_EOL;
        $imageData = $element->getImageStringForRendering();
        $content .= $imageData === null ? '' : bin2hex($imageData);
        $content .= '}}';
        $content .= $this->writeClosing();

        return $content;
    }
}
