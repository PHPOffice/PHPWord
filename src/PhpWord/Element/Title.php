<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Shared\String;

/**
 * Title element
 */
class Title extends AbstractElement
{
    /**
     * Title Text content
     *
     * @var string
     */
    private $text;

    /**
     * Title depth
     *
     * @var int
     */
    private $depth;

    /**
     * Title anchor
     *
     * @var int
     */
    private $anchor;

    /**
     * Title Bookmark ID
     *
     * @var int
     */
    private $bookmarkId = 1;

    /**
     * Name of the heading style, e.g. 'Heading1'
     *
     * @var string
     */
    private $style;


    /**
     * Create a new Title Element
     *
     * @param string $text
     * @param int $depth
     * @param string $style
     */
    public function __construct($text, $depth = 1)
    {

        $this->text = String::toUTF8($text);
        $this->depth = $depth;
        if (array_key_exists('Heading_' . $this->depth, Style::getStyles())) {
            $this->style = 'Heading' . $this->depth;
        }

        return $this;
    }

    /**
     * Set Bookmark ID
     *
     * @param int $bookmarkId
     */
    public function setBookmarkId($bookmarkId)
    {
        $this->bookmarkId = $bookmarkId;
    }

    /**
     * Get Anchor
     *
     * @return int
     */
    public function getBookmarkId()
    {
        return $this->bookmarkId;
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
     * @return integer
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
     * Set Anchor
     *
     * @param int $anchor
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function setAnchor($anchor)
    {
        $this->anchor = $anchor;
    }

    /**
     * Get Anchor
     *
     * @return int
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getAnchor()
    {
        return '_Toc' . (252634154 + $this->bookmarkId);
    }
}
