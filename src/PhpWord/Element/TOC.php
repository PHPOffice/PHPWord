<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\TOC as Titles;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\TOC as TOCStyle;

/**
 * Table of contents
 */
class TOC extends AbstractElement
{
    /**
     * TOC style
     *
     * @var \PhpOffice\PhpWord\Style\TOC
     */
    private $TOCStyle;

    /**
     * Font style
     *
     * @var \PhpOffice\PhpWord\Style\Font|array|string
     */
    private $fontStyle;

    /**
     * Min title depth to show
     *
     * @var int
     */
    private $minDepth = 1;

    /**
     * Max title depth to show
     *
     * @var int
     */
    private $maxDepth = 9;


    /**
     * Create a new Table-of-Contents Element
     *
     * @param mixed $fontStyle
     * @param array $tocStyle
     * @param integer $minDepth
     * @param integer $maxDepth
     */
    public function __construct($fontStyle = null, $tocStyle = null, $minDepth = 1, $maxDepth = 9)
    {
        $this->TOCStyle = new TOCStyle();

        if (!is_null($tocStyle) && is_array($tocStyle)) {
            foreach ($tocStyle as $key => $value) {
                 $this->TOCStyle->setStyleValue($key, $value);
            }
        }

        if (!is_null($fontStyle)) {
            if (is_array($fontStyle)) {
                 $this->fontStyle = new Font();
                foreach ($fontStyle as $key => $value) {
                     $this->fontStyle->setStyleValue($key, $value);
                }
            } else {
                 $this->fontStyle = $fontStyle;
            }
        }

        $this->minDepth = $minDepth;
        $this->maxDepth = $maxDepth;
    }

    /**
     * Get all titles
     *
     * @return array
     */
    public function getTitles()
    {
        $titles = Titles::getTitles();
        foreach ($titles as $i => $title) {
            if ($this->minDepth > $title['depth']) {
                unset($titles[$i]);
            }
            if (($this->maxDepth != 0) && ($this->maxDepth < $title['depth'])) {
                unset($titles[$i]);
            }
        }
        $titles = array_merge(array(), $titles);

        return $titles;
    }

    /**
     * Get TOC Style
     *
     * @return \PhpOffice\PhpWord\Style\TOC
     */
    public function getStyleTOC()
    {
        return $this->TOCStyle;
    }

    /**
     * Get Font Style
     *
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function getStyleFont()
    {
        return $this->fontStyle;
    }

    /**
     * Set max depth
     *
     * @param int $value
     */
    public function setMaxDepth($value)
    {
        $this->maxDepth = $value;
    }

    /**
     * Get Max Depth
     *
     * @return int Max depth of titles
     */
    public function getMaxDepth()
    {
        return $this->maxDepth;
    }

    /**
     * Set min depth
     *
     * @param int $value
     */
    public function setMinDepth($value)
    {
        $this->minDepth = $value;
    }

    /**
     * Get Min Depth
     *
     * @return int Min depth of titles
     */
    public function getMinDepth()
    {
        return $this->minDepth;
    }
}
