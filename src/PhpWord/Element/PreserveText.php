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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\Common\Text as CommonText;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Preserve text/field element
 */
class PreserveText extends AbstractElement
{
    /**
     * Text content
     *
     * @var string
     */
    private $text;

    /**
     * Text style
     *
     * @var string|\PhpOffice\PhpWord\Style\Font
     */
    private $fontStyle;

    /**
     * Paragraph style
     *
     * @var string|\PhpOffice\PhpWord\Style\Paragraph
     */
    private $paragraphStyle;


    /**
     * Create a new Preserve Text Element
     *
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     */
    public function __construct($text = null, $fontStyle = null, $paragraphStyle = null)
    {
        $this->fontStyle = $this->setNewStyle(new Font('text'), $fontStyle);
        $this->paragraphStyle = $this->setNewStyle(new Paragraph(), $paragraphStyle);

        $this->text = CommonText::toUTF8($text);
        $matches = preg_split('/({.*?})/', $this->text, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        if (isset($matches[0])) {
            $this->text = $matches;
        }

        return $this;
    }

    /**
     * Get Text style
     *
     * @return string|\PhpOffice\PhpWord\Style\Font
     */
    public function getFontStyle()
    {
        return $this->fontStyle;
    }

    /**
     * Get Paragraph style
     *
     * @return string|\PhpOffice\PhpWord\Style\Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->paragraphStyle;
    }

    /**
     * Get Text content
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
