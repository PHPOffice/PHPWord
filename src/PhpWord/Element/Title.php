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

use PhpOffice\PhpWord\Shared\Text as SharedText;
use PhpOffice\PhpWord\Style;

/**
 * Title element
 */
class Title extends AbstractElement
{
    /**
     * Title Text content
     *
     * @var string|TextRun
     */
    private $text;

    /**
     * Title depth
     *
     * @var int
     */
    private $depth = 1;

    /**
     * Name of the heading style, e.g. 'Heading1'
     *
     * @var string
     */
    private $style;

    /**
     * Depends on Word version, the titles are not always named "Heading1" …
     * The prefix "Heading" is named "Titre" in French, "Titulo" en Spanish….
     *
     * @var string
     */
    protected static $styleNamePrefix = null;

    /**
     * Is part of collection.
     *
     * @var bool
     */
    protected $collectionRelation = true;

    /**
     * Create a new Title Element
     *
     * @param string|TextRun $text
     * @param int $depth
     */
    public function __construct($text, $depth = 1)
    {
        if (is_string($text)) {
            $this->text = SharedText::toUTF8($text);
        } elseif ($text instanceof TextRun) {
            $this->text = $text;
        } else {
            throw new \InvalidArgumentException('Invalid text, should be a string or a TextRun');
        }

        // Default is Heading1, Heading2 … if no prefix has been registered by the style reader
        if (is_null(self::$styleNamePrefix)) {
            self::$styleNamePrefix = 'Heading';
        }

        $this->depth = $depth;
        $styleName = $depth === 0 ? 'Title' : self::$styleNamePrefix . $this->depth;

        if (array_key_exists($styleName, Style::getStyles())) {
            $this->style = $styleName; // Useless ? str_replace('_', '', $styleName);
        }
    }

    /**
     * Get Title Text content
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get depth
     *
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Get Title style
     *
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set a custom title suffix.
     * The prefix "Heading" is named "Titre" in French, "Titulo" en Spanish…
     * It necessary to make it custom by the Style Reader.
     */
    public static function registerTitleStylePrefix(string $prefix)
    {
        if (is_null(self::$styleNamePrefix)) {
            self::$styleNamePrefix = $prefix;
        }
    }
}
