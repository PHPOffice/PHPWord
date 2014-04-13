<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

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
    private $bookmarkId;

    /**
     * Title style
     *
     * @var string
     */
    private $style;


    /**
     * Create a new Title Element
     *
     * @param string $text
     * @param int $depth
     * @param string $style Name of the heading style, e.g. 'Heading1'
     */
    public function __construct($text, $depth = 1, $style = null)
    {
        if (!is_null($style)) {
            $this->style = $style;
        }

        $this->text = $text;
        $this->depth = $depth;

        return $this;
    }

    /**
     * Set Anchor
     *
     * @param int $anchor
     */
    public function setAnchor($anchor)
    {
        $this->anchor = $anchor;
    }

    /**
     * Get Anchor
     *
     * @return int
     */
    public function getAnchor()
    {
        return $this->anchor;
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
}
