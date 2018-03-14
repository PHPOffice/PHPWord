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

namespace PhpOffice\PhpWord\Element;

use PhpOffice\Common\Text as CommonText;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Link element
 */
class Link extends AbstractElement
{
    /**
     * Link source
     *
     * @var string
     */
    private $source;

    /**
     * Link text
     *
     * @var string
     */
    private $text;

    /**
     * Font style
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
     * Has media relation flag; true for Link, Image, and Object
     *
     * @var bool
     */
    protected $mediaRelation = true;

    /**
     * Has internal flag - anchor to internal bookmark
     *
     * @var bool
     */
    protected $internal = false;

    /**
     * Create a new Link Element
     *
     * @param string $source
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @param bool $internal
     */
    public function __construct($source, $text = null, $fontStyle = null, $paragraphStyle = null, $internal = false)
    {
        $this->source = CommonText::toUTF8($source);
        $this->text = is_null($text) ? $this->source : CommonText::toUTF8($text);
        $this->fontStyle = $this->setNewStyle(new Font('text'), $fontStyle);
        $this->paragraphStyle = $this->setNewStyle(new Paragraph(), $paragraphStyle);
        $this->internal = $internal;
    }

    /**
     * Get link source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get link text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
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
     * Get link target
     *
     * @deprecated 0.12.0
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getTarget()
    {
        return $this->source;
    }

    /**
     * Get Link source
     *
     * @deprecated 0.10.0
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getLinkSrc()
    {
        return $this->getSource();
    }

    /**
     * Get Link name
     *
     * @deprecated 0.10.0
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getLinkName()
    {
        return $this->getText();
    }

    /**
     * is internal
     *
     * @return bool
     */
    public function isInternal()
    {
        return $this->internal;
    }
}
